<?php

class AuthController extends Controller
{
  private AuthService $authService;


  public function __construct()
  {
    $db = Database::getConnection();
    $userRepository = new UserRepository($db);
    $passwordResetRepository = new PasswordResetRepository($db);
    $rememberTokenRepository = new RememberTokenRepository($db);
    $this->authService = new AuthService($userRepository, $passwordResetRepository, $rememberTokenRepository);
  }

  public function showLogin(): void
  {
    $this->view('auth/login', [
      'title' => 'Login',
      'scripts' => ['/assets/js/pages/login.js'],
    ], 'auth');
  }

  public function login(): void
  {
    $this->validateCsrfToken();

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === 'true';

    $errors = [];

    if ($email === '') {
      $errors['email'] = 'Email là bắt buộc';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Định dạng email không hợp lệ';
    }

    if ($password === '') {
      $errors['password'] = 'Mật khẩu là bắt buộc';
    }

    if (!empty($errors)) {
      $this->json([
        'success' => false,
        'message' => 'Vui lòng kiểm tra lại thông tin',
        'errors' => $errors,
      ], 422);
    }

    $user = $this->authService->login($email, $password, $rememberMe);

    session_regenerate_id(true);

    // Set user session
    $_SESSION['user_id'] = $user->id;
    $_SESSION['email'] = $user->email;
    $_SESSION['user_name'] = $user->username;
    $_SESSION['name'] = $user->firstName . " " . $user->lastName;
    $_SESSION['avatar'] = $user->avatar;
    $_SESSION['logged_in'] = true;

    $this->json([
      'success' => true,
      'message' => 'Đăng nhập thành công',
      'redirect' => '/',
    ]);
  }

  public function showRegister(): void
  {
    $this->view('auth/register', [
      'title' => 'Register',
      'scripts' => ['/assets/js/pages/register.js'],
    ], 'auth');
  }

  public function register(): void
  {
    $this->validateCsrfToken();

    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $data = [
      'first_name' => trim($_POST['first_name'] ?? ''),
      'last_name' => trim($_POST['last_name'] ?? ''),
      'username' => trim($_POST['username'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'password' => trim($_POST['password'] ?? ''),
    ];

    $errors = [];

    if ($data['first_name'] === '') {
      $errors['first_name'] = 'Họ là bắt buộc';
    }

    if ($data['last_name'] === '') {
      $errors['last_name'] = 'Tên là bắt buộc';
    }

    if ($data['username'] === '') {
      $errors['username'] = 'Tên đăng nhập là bắt buộc';
    } elseif (strlen($data['username']) < 3) {
      $errors['username'] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
    }

    if ($data['email'] === '') {
      $errors['email'] = 'Email là bắt buộc';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Định dạng email không hợp lệ';
    }

    if ($data['password'] === '') {
      $errors['password'] = 'Mật khẩu là bắt buộc';
    } elseif (strlen($data['password']) < 6) {
      $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
    }

    if ($confirmPassword === '') {
      $errors['confirm_password'] = 'Xác nhận mật khẩu là bắt buộc';
    } elseif ($data['password'] !== $confirmPassword) {
      $errors['confirm_password'] = 'Xác nhận mật khẩu không đúng';
    }

    if (!empty($errors)) {
      $this->json([
        'success' => false,
        'message' => 'Vui lòng kiểm tra lại thông tin',
        'errors' => $errors,
      ], 422);
    }


    $this->authService->register($data);

    $this->json([
      'success' => true,
      'message' => 'Đăng kí thành công. Vui lòng đăng nhập.',
      'redirect' => '/login',
    ], 201);
  }

  public function logout(): void
  {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
      throw new HttpException('Phương thức không được phép', 405);
    }

    $this->validateCsrfToken();

    $_SESSION = [];
    session_destroy();
    $this->authService->logOut();
    $this->redirect('/login');
  }

  public function showForgot(): void
  {
    $this->view('auth/forgot', [
      'title' => 'Quên mật khẩu',
      'scripts' => ['/assets/js/pages/forgot.js'],
    ], 'auth');
  }

  public function sendForgot(): void
  {
    $this->validateCsrfToken();

    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->json(['success' => false, 'message' => 'Email không hợp lệ'], 422);
    }

    $this->authService->sendForgotPassword($email);

    $this->json(['success' => true, 'message' => 'Nếu email tồn tại trong hệ thống, bạn sẽ nhận được hướng dẫn đặt lại mật khẩu.']);
  }

  public function showReset(): void
  {
    $token = $_GET['token'] ?? '';
    $this->view('auth/reset', [
      'title' => 'Đặt lại mật khẩu',
      'token' => $token,
      'scripts' => ['/assets/js/pages/reset.js'],
    ], 'auth');
  }

  public function reset(): void
  {
    $this->validateCsrfToken();

    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password === '' || strlen($password) < 6) {
      $this->json(['success' => false, 'message' => 'Mật khẩu không hợp lệ', 'errors' => ['password' => 'Mật khẩu phải có ít nhất 6 ký tự']], 422);
    }

    if ($password !== $confirm) {
      $this->json(['success' => false, 'message' => 'Xác nhận mật khẩu không khớp', 'errors' => ['confirm_password' => 'Xác nhận mật khẩu không đúng']], 422);
    }

    $this->authService->resetPassword($token, $password);

    $this->json(['success' => true, 'message' => 'Mật khẩu đã được cập nhật. Vui lòng đăng nhập.']);
  }
}
