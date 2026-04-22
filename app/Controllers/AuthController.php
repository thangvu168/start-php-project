<?php

class AuthController extends Controller
{
  private AuthService $authService;


  public function __construct()
  {
    $db = Database::getConnection();
    $userRepository = new UserRepository($db);
    $this->authService = new AuthService($userRepository);
  }

  public function showLogin(): void
  {
    $this->view('auth/login', [
      'title' => 'Login',
      'error' => $_SESSION['error'] ?? '',
      'success' => $_SESSION['success'] ?? '',
      'old' => [],
    ], 'auth');

    unset($_SESSION['error'], $_SESSION['success']);
  }

  public function showLoginForm(): void
  {
    $this->showLogin();
  }

  public function login(): void
  {
    $this->validateCsrfToken();

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

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

    $user = $this->authService->login($email, $password);

    if (!$user) {
      error_log('[LOGIN FAILED] Invalid credentials: ' . json_encode([
        'email' => $email,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
      ]));

      $this->json([
        'success' => false,
        'message' => 'Email hoặc mật khẩu không đúng',
      ], 401);
    }

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
      'error' => '',
      'old' => [],
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
      error_log('[REGISTER VALIDATION FAILED] ' . json_encode([
        'data' => $data,
        'errors' => $errors,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
      ]));

      $this->json([
        'success' => false,
        'message' => 'Vui lòng kiểm tra lại thông tin',
        'errors' => $errors,
      ], 422);
    }

    try {
      $this->authService->register($data);

      $this->json([
        'success' => true,
        'message' => 'Đăng kí thành công. Vui lòng đăng nhập.',
        'redirect' => '/login',
      ], 201);
    } catch (Exception $e) {

      error_log('[REGISTER ERROR] ' . json_encode([
        'data' => $data,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
      ]));


      $this->json([
        'success' => false,
        'message' => $e->getMessage(),
        'errors' => [],
      ], 400);
    }
  }

  public function logout(): void
  {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
      throw new HttpException('Phương thức không được phép', 405);
    }

    $this->validateCsrfToken();

    $_SESSION = [];
    session_destroy();
    $this->redirect('/login');
  }
}
