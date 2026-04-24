<?php

class AuthController extends Controller
{
  private AuthService $authService;
  private LoginAttemptService $loginAttemptService;

  public function __construct()
  {
    $db = Database::getConnection();
    $userRepository = new UserRepository($db);
    $passwordResetRepository = new PasswordResetRepository($db);
    $rememberTokenRepository = new RememberTokenRepository($db);
    $loginAttemptRepository = new LoginAttemptRepository($db);
    $this->authService = new AuthService($userRepository, $passwordResetRepository, $rememberTokenRepository);
    $this->loginAttemptService = new LoginAttemptService($loginAttemptRepository);
  }

  public function showLogin(): void
  {
    $ip = getClientIp();
    $attemptCount = $this->loginAttemptService->getAttempts($ip);
    $needsCaptcha = $this->loginAttemptService->needsCaptcha($ip);

    $this->view('auth/login', [
      'title' => 'Login',
      'scripts' => ['/assets/js/pages/login.js'],
      'recaptchaSiteKey' => config('recaptcha.sitekey'),
      'needsCaptcha' => $needsCaptcha,
      'attemptCount' => $attemptCount,
    ], 'auth');
  }

  public function login(): void
  {
    $this->validateCsrfToken();

    $ip = getClientIp();
    $needsCaptcha = $this->loginAttemptService->needsCaptcha($ip);

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === 'true';
    $captchaToken = trim($_POST['g-recaptcha-response'] ?? '');

    $errors = [];

    if ($email === '') {
      $errors['email'] = 'Email là bắt buộc';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Định dạng email không hợp lệ';
    }

    if ($password === '') {
      $errors['password'] = 'Mật khẩu là bắt buộc';
    }

    // Check if captcha is required but not provided
    if ($needsCaptcha && empty($captchaToken)) {
      $this->json([
        'success' => false,
        'message' => 'Vui lòng hoàn thành xác minh reCAPTCHA',
        'errors' => [],
      ], 429);
      return;
    }

    if (!empty($errors)) {
      $this->json([
        'success' => false,
        'message' => 'Vui lòng kiểm tra lại thông tin',
        'errors' => $errors,
      ], 422);
      return;
    }

    try {
      $user = $this->authService->login($email, $password, $rememberMe);

      // Login successful - reset attempts
      $this->loginAttemptService->reset($ip);
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
    } catch (HttpException $e) {
      // Login failed - increment attempts
      $newAttemptCount = $this->loginAttemptService->increment($ip);

      // If now >= 3 attempts, tell client to reload
      if ($newAttemptCount >= 3) {
        $this->json([
          'success' => false,
          'message' => $e->getMessage(),
          'reload' => true,
        ], 401);
      } else {
        $this->json([
          'success' => false,
          'message' => $e->getMessage(),
        ], 401);
      }
    }
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

    $confirmPassword = $_POST['confirm_password'] ?? '';
    $data = [
      'first_name' => trim($_POST['first_name'] ?? ''),
      'last_name' => trim($_POST['last_name'] ?? ''),
      'username' => trim($_POST['username'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'password' => $_POST['password'] ?? '',
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
      return;
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
      return;
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
      return;
    }

    if ($password !== $confirm) {
      $this->json(['success' => false, 'message' => 'Xác nhận mật khẩu không khớp', 'errors' => ['confirm_password' => 'Xác nhận mật khẩu không đúng']], 422);
      return;
    }

    $this->authService->resetPassword($token, $password);

    $this->json(['success' => true, 'message' => 'Mật khẩu đã được cập nhật. Vui lòng đăng nhập.']);
  }
}
