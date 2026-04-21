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

  public function home(): void
  {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== 'TRUE') {
      $this->redirect('/login');
      return;
    }

    echo '<h1>Profile Page</h1>';
    echo '<p>Hello, ' . htmlspecialchars($_SESSION['user_name'] ?? 'User') . '</p>';
    echo '<a href="/logout">Logout</a>';
  }

  public function showLogin(): void
  {
    $this->view('auth/login', [
      'title' => 'Login',
      'error' => $_SESSION['error'] ?? '',
      'success' => $_SESSION['success'] ?? '',
      'old' => [],
    ]);

    unset($_SESSION['error'], $_SESSION['success']);
  }

  public function showLoginForm(): void
  {
    $this->showLogin();
  }

  public function login(): void
  {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
      $this->view('auth/login', [
        'title' => 'Login',
        'error' => 'Please enter email and password',
        'success' => '',
        'old' => ['email' => $email],
      ]);
      return;
    }

    $user = $this->authService->login($email, $password);

    if (!$user) {
      $this->view('auth/login', [
        'title' => 'Login',
        'error' => 'Invalid email or password',
        'success' => '',
        'old' => ['email' => $email],
      ]);
      return;
    }

    // Set user session
    $_SESSION['user_id'] = $user->id;
    $_SESSION['email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['logged_in'] = 'TRUE';

    // Redirect to home page
    $this->redirect('/');
  }

  public function showRegister(): void
  {
    $this->view('auth/register', [
      'title' => 'Register',
      'error' => '',
      'old' => [],
    ]);
  }

  public function showRegisterForm(): void
  {
    $this->showRegister();
  }

  public function register(): void
  {
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $data = [
      'first_name' => trim($_POST['first_name'] ?? ''),
      'last_name' => trim($_POST['last_name'] ?? ''),
      'username' => trim($_POST['username'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'password' => trim($_POST['password'] ?? ''),
    ];

    if (
      $data['email'] === '' ||
      $data['username'] === '' ||
      $data['password'] === ''
    ) {
      $this->view('auth/register', [
        'title' => 'Register',
        'error' => 'Please fill all required fields',
        'old' => $data,
      ]);
      return;
    }

    if ($data['password'] !== $confirmPassword) {
      $this->view('auth/register', [
        'title' => 'Register',
        'error' => 'Confirm password is incorrect',
        'old' => $data,
      ]);
      return;
    }

    try {
      $this->authService->register($data);
      $_SESSION['success'] = 'Registration successful. Please login.';
      $this->redirect('/login');
    } catch (Exception $e) {
      $this->view('auth/register', [
        'title' => 'Register',
        'error' => $e->getMessage(),
        'old' => $data,
      ]);
    }
  }

  public function logout(): void
  {
    $_SESSION = [];
    session_destroy();
    $this->redirect('/login');
  }
}
