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

        $this->view('profile/index', [
            'title'   => 'Profile',
            'user'    => $user,
            'success' => $success
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

        $errors = [];

        if ($firstName === '') {
            $errors['first_name'] = 'First name is required';
        }

        if ($lastName === '') {
            $errors['last_name'] = 'Last name is required';
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
            $this->json([
                'success' => false,
                'message' => 'Please check your input',
                'errors' => $errors,
            ], 422);
        }

        try {
            $uploadService = new UploadService();

            $avatarPath = null;

            if (!empty($_FILES['avatar']['name'])) {
                $avatarPath = $uploadService->uploadImage($_FILES['avatar']);
            }

            // Update DB
            $this->userService->updateProfile((int) $userId, $firstName, $lastName, $avatarPath);

            $_SESSION['name'] = $firstName . ' ' . $lastName;
            if ($avatarPath !== null) {
                $_SESSION['avatar'] = $avatarPath;
            }

            $this->json([
                'success' => true,
                'message' => 'Update profile success',
                'data' => [
                    'name' => $_SESSION['name'],
                    'avatar' => $_SESSION['avatar'] ?? null,
                ]
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 400);
        }
    }
}
