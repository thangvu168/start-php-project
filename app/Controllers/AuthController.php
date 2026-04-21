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
      $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email format is invalid';
    }

    if ($password === '') {
      $errors['password'] = 'Password is required';
    }

    if (!empty($errors)) {
      $this->json([
        'success' => false,
        'message' => 'Please check your input',
        'errors' => $errors,
      ], 422);
    }

    $user = $this->authService->login($email, $password);

    if (!$user) {
      $this->json([
        'success' => false,
        'message' => 'Invalid email or password',
        'errors' => [
          'email' => 'Invalid email or password',
          'password' => 'Invalid email or password',
        ],
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
      'message' => 'Login successful',
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

  public function showRegisterForm(): void
  {
    $this->showRegister();
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
      $errors['username'] = 'Username is required';
    } elseif (mb_strlen($data['username']) < 3) {
      $errors['username'] = 'Username must be at least 3 characters';
    }

    if ($data['email'] === '') {
      $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email format is invalid';
    }

    if ($data['password'] === '') {
      $errors['password'] = 'Password is required';
    } elseif (mb_strlen($data['password']) < 6) {
      $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($confirmPassword === '') {
      $errors['confirm_password'] = 'Confirm password is required';
    } elseif ($data['password'] !== $confirmPassword) {
      $errors['confirm_password'] = 'Confirm password is incorrect';
    }

    if (!empty($errors)) {
      $this->json([
        'success' => false,
        'message' => 'Please check your input',
        'errors' => $errors,
      ], 422);
    }

    try {
      $this->authService->register($data);

      $this->json([
        'success' => true,
        'message' => 'Registration successful. Please login.',
        'redirect' => '/login',
      ], 201);
    } catch (Exception $e) {
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
      throw new HttpException('Method not allowed', 405);
    }

    $this->validateCsrfToken();

    $_SESSION = [];
    session_destroy();
    $this->redirect('/login');
  }
}
