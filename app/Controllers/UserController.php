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

    public function redirectToProfile(): void
    {
        $this->redirect('/profile');
    }

    public function showProfile(): void
    {
        $user = $this->userService->getProfile($_SESSION['user_id']);


        $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

        $this->view('profile/index', [
            'title'   => 'Hồ sơ',
            'user'    => $user,
            'aside'   => 'Views/profile/aside',
            'scripts' => [
                '/assets/js/modules/user.js',
                '/assets/js/pages/profile.js',
            ],
            'page_header' => [
                'subtitle' => 'Hồ sơ',
                'title'    => $fullName,
                'back_url' => '/',
                'buttons'  => [
                    ['text' => 'Chỉnh sửa hồ sơ', 'class' => 'btn-primary', 'id' => 'btnEditProfile'],
                ],
            ],
        ]);
    }

    public function changeProfile(): void
    {
        $this->validateCsrfToken();

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

        if ($phone !== '' && !preg_match(ValidationRules::PHONE, $phone)) {
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
                return;
            } else {
                $_SESSION['errors'] = $errors;
                $this->redirect('/profile');
            }
        }


        $uploadService = new UploadService();
        $removeAvatar = ($_POST['remove_avatar'] ?? '') === '1';

        $avatarPath = null;

        if (!empty($_FILES['avatar']['name'])) {
            $avatarPath = $uploadService->uploadImage($_FILES['avatar']);
        }

        // Update DB
        $this->userService->updateProfile($_SESSION['user_id'], $firstName, $lastName, $phone, $avatarPath, $removeAvatar);

        $_SESSION['name'] = $firstName . ' ' . $lastName;
        if ($avatarPath !== null) {
            $_SESSION['avatar'] = $avatarPath;
        } elseif ($removeAvatar) {
            $_SESSION['avatar'] = null;
        }

        $this->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ thành công',
            'data' => [
                'name' => $_SESSION['name'],
                'avatar' => $_SESSION['avatar'] ?? null,
            ]
        ]);
    }
}
