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

    public function showProfile()
    {
        $userId = $_SESSION['user_id'] ?? null;

        $user = $this->userService->getProfile($userId);

        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        $this->view('profile/index', [
            'title'   => 'Profile',
            'user'    => $user,
            'success' => $success
        ]);
    }

    public function changeProfile()
    {
        $userId = $_SESSION['user_id'];

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');

        if ($firstName === '' || $lastName === '') {
            $user = $this->userService->getProfile($userId);

            $this->view('profile/index', [
                'title' => 'Profile',
                'error' => 'Please fill all required fields',
                'user'  => $user
            ]);
            return;
        }

        try {
            $uploadService = new UploadService();

            $avatarPath = null;

            if (!empty($_FILES['avatar']['name'])) {
                $avatarPath = $uploadService->uploadImage($_FILES['avatar']);
            }

            // Update DB
            $this->userService->updateProfile($userId, $firstName, $lastName, $avatarPath);

            $_SESSION['name'] = $firstName . ' ' . $lastName;
            $_SESSION['avatar'] = $avatarPath;
            $_SESSION['success'] = 'Update profile success';

            $this->redirect('/profile');
        } catch (Exception $e) {
            $user = $this->userService->getProfile($userId);

            $this->view('profile/index', [
                'title' => 'Profile',
                'user'  => $user,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
