<?php

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $db = Database::getConnection();
        $userRepository = new UserRepository($db);
        $this->userService = new UserService($userRepository);
    }

    public function showProfile(): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId === null) {
            $this->redirect('/login');
        }

        $user = $this->userService->getProfile((int) $userId);

        if ($user === null) {
            throw new HttpException('User not found', 404);
        }

        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->view('profile/index', [
            'title'   => 'Profile',
            'user'    => $user,
            'success' => $success,
            'error'   => $error,
            'errors'  => $errors
        ]);
    }

    public function changeProfile(): void
    {
        $this->validateCsrfToken();

        $userId = $_SESSION['user_id'] ?? null;

        if ($userId === null) {
            $this->json([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' => [],
                'redirect' => '/login'
            ], 401);
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');

        $errors = [];

        if ($firstName === '') {
            $errors['first_name'] = 'Họ là bắt buộc';
        }

        if ($lastName === '') {
            $errors['last_name'] = 'Tên là bắt buộc';
        }

        if ($phone !== '' && !preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $phone)) {
            $errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        if (!empty($_FILES['avatar']['name'] ?? '')) {
            $maxSize = 2 * 1024 * 1024;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $avatarType = $_FILES['avatar']['type'] ?? '';
            $avatarSize = (int) ($_FILES['avatar']['size'] ?? 0);

            if (!in_array($avatarType, $allowedTypes, true)) {
                $errors['avatar'] = 'Avatar must be jpeg, png or webp';
            } elseif ($avatarSize > $maxSize) {
                $errors['avatar'] = 'Avatar size must be less than 2MB';
            }
        }

        if (!empty($errors)) {
            if ($this->isJsonRequest()) {
                $this->json([
                    'success' => false,
                    'message' => 'Vui lòng kiểm tra thông tin',
                    'errors' => $errors,
                ], 422);
            } else {
                $_SESSION['errors'] = $errors;
                $this->redirect('/profile');
            }
        }

        try {
            $uploadService = new UploadService();

            $avatarPath = null;

            if (!empty($_FILES['avatar']['name'])) {
                $avatarPath = $uploadService->uploadImage($_FILES['avatar']);
            }

            // Update DB
            $this->userService->updateProfile((int) $userId, $firstName, $lastName, $phone, $avatarPath);

            $_SESSION['name'] = $firstName . ' ' . $lastName;
            if ($avatarPath !== null) {
                $_SESSION['avatar'] = $avatarPath;
            }

            if ($this->isJsonRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cập nhật hồ sơ thành công',
                    'data' => [
                        'name' => $_SESSION['name'],
                        'avatar' => $_SESSION['avatar'] ?? null,
                    ]
                ]);
            } else {
                $_SESSION['success'] = 'Cập nhật hồ sơ thành công';
                $this->redirect('/profile');
            }
        } catch (Exception $e) {
            if ($this->isJsonRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => []
                ], 400);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('/profile');
            }
        }
    }
}
