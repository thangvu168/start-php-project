<?php

class AuthController extends Controller
{
  private AuthService $authService;
  private UserService $userService;


  public function __construct()
  {
    $db = Database::getConnection();
    $userRepository = new UserRepository($db);
    $passwordResetRepository = new PasswordResetRepository($db);
    $this->authService = new AuthService($userRepository, $passwordResetRepository);
    $this->userService = new UserService($userRepository);
  }

  public function showLogin(): void
  {
    $this->view('auth/login', [
      'title' => 'Login',
      'scripts' => ['/assets/js/pages/login.js'],
    ], 'auth');
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

    // Check user name and email already exist
    if ($this->userService->userExists($data['username'], $data['email'])) {
      $this->json([
        'success' => false,
        'message' => 'Tên đăng nhập hoặc email đã tồn tại',
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
    $captcha = $_POST['g-recaptcha-response'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->json(['success' => false, 'message' => 'Email không hợp lệ'], 422);
    }

    if (!empty(config('recaptcha.sitekey'))) {
      $ok = $this->authService->verifyRecaptcha($captcha);
      if (!$ok) {
        $this->json(['success' => false, 'message' => 'Captcha không hợp lệ'], 422);
      }
    }

    $token = $this->authService->createResetToken($email);


    if (!empty($token)) {
      $link = sprintf('%s/password/reset?token=%s', $this->getBaseUrl(), urlencode($token));
      $viewFile = __DIR__ . '/../Views/emails/password_reset.php';
      ob_start();
      $link = $link;
      require $viewFile;
      $body = ob_get_clean();
      $mailer = new MailService();
      $mailer->send($email, 'Yêu cầu đặt lại mật khẩu', $body);
      $this->json([
        'success' => true,
        'message' => 'Kiểm tra email của bạn để nhận đường dẫn đặt lại mật khẩu.',
      ]);
    } else {
      $this->json(['success' => false, 'message' => 'Tài khoản không tồn tại. Vui lòng thử lại sau.'], 400);
    }
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

    $userId = $this->authService->verifyToken($token);
    if (!$userId) {
      $this->json(['success' => false, 'message' => 'Token không hợp lệ hoặc đã hết hạn'], 400);
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $this->userService->changePassword($userId, $hashed);

    $this->authService->consumeToken($token);

    $this->json(['success' => true, 'message' => 'Mật khẩu đã được cập nhật. Vui lòng đăng nhập.']);
  }

  private function getBaseUrl(): string
  {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
  }
}
