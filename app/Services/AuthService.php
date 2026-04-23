<?php

class AuthService
{
  private const REMEMBER_TOKEN_KEY = 'remember_token';
  private const REMEMBER_TOKEN_EXPIRY = 7 * 24 * 3600;

  private MailService $mailService;

  public function __construct(
    private UserRepository $userRepository,
    private PasswordResetRepository $passwordResetRepository,
    private RememberTokenRepository $rememberTokenRepository,
  ) {
    // TODO: Using queue to send mail
    $this->mailService = new MailService();
  }

  public function login(string $email, string $password, bool $rememberMe = false): User
  {
    $user = $this->getUserByEmail($email);

    if (!$user) {
      throw new HttpException("Email hoặc mật khẩu không đúng", 401);
    }

    if (!password_verify($password, $user->password)) {
      throw new HttpException("Email hoặc mật khẩu không đúng", 401);
    }

    if ($rememberMe) {
      $token = bin2hex(random_bytes(32));
      $hash = hash('sha256', $token);
      $expires = date('Y-m-d H:i:s', time() + $this::REMEMBER_TOKEN_EXPIRY);

      $this->rememberTokenRepository->create([
        'user_id' => $user->id,
        'token' => $hash,
        'expires_at' => $expires,
      ]);

      setcookie(
        $this::REMEMBER_TOKEN_KEY,
        $token,
        time() + $this::REMEMBER_TOKEN_EXPIRY,
        '/', // path
        '', // domain
        isset($_SERVER['HTTPS']), // secure
        true // httpOnly
      );
    }

    return $user;
  }

  public function register(array $data): int
  {
    // Check if email already exists
    if ($this->getUserByEmail($data['email'])) {
      throw new HttpException("Email đã tồn tại", 422);
    }

    if ($this->getUserByUsername($data['username'])) {
      throw new HttpException("Tên đăng nhập đã tồn tại", 422);
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    return $this->userRepository->create([
      'first_name' => $data['first_name'],
      'last_name' => $data['last_name'],
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => $hashedPassword,
    ]);
  }

  public function logOut()
  {
    // Delete remember token from database
    if (isset($_COOKIE[$this::REMEMBER_TOKEN_KEY])) {
      $token = $_COOKIE[$this::REMEMBER_TOKEN_KEY];
      $hash = hash('sha256', $token);
      $this->rememberTokenRepository->execute(
        'DELETE FROM remember_tokens WHERE token = ?',
        [$hash]
      );
    }

    // Delete cookie
    // time() - 3600 means set the cookie expiration time to one hour ago, effectively deleting it from the browser
    setcookie($this::REMEMBER_TOKEN_KEY, '', time() - 3600, '/');
  }

  public function sendForgotPassword(string $email): void
  {
    $user = $this->getUserByEmail($email);
    if (!$user) {
      throw new HttpException("Email không tồn tại", 422);
    }

    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', time() + 3600);

    $this->passwordResetRepository->create([
      'email' => $email,
      'token_hash' => $hash,
      'expires_at' => $expires,
    ]);

    // Send mail
    // TODO: Using queue to send mail
    $link = sprintf('%s/password/reset?token=%s', $this->getBaseUrl(), urlencode($token));
    $viewFile = __DIR__ . '/../Views/emails/password_reset.php';
    ob_start();
    $link = $link;
    require $viewFile;
    $body = ob_get_clean();
    $this->mailService->send($email, 'Yêu cầu đặt lại mật khẩu', $body);
  }

  public function resetPassword(string $token, string $newPassword): void
  {
    // 1. Hash token
    $hashedToken = hash('sha256', $token);

    // 2. Find record by hashed token
    $record = $this->passwordResetRepository->execute(
      'SELECT * FROM password_resets WHERE token_hash = ?',
      [$hashedToken]
    );

    // 3. If not found or expired, throw error
    if (empty($record) || strtotime($record[0]['expires_at']) < time()) {
      throw new HttpException("Token không hợp lệ hoặc đã hết hạn", 422);
    }

    // 4. Check user exists
    $email = $record[0]['email'];
    $users = $this->userRepository->execute(
      "SELECT id FROM users WHERE email = ?",
      [$email]
    );
    if (empty($users)) {
      throw new HttpException("Người dùng không tồn tại", 422);
    }

    // 5. Xóa token và cập nhật mật khẩu mới
    $this->passwordResetRepository->delete($record[0]['id']);

    // 6. Cập nhật mật khẩu mới
    $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $this->userRepository->update($users[0]['id'], ['password' => $hashPassword]);
  }

  private function getUserByEmail(string $email): ?User
  {
    $users = $this->userRepository->execute(
      "SELECT * FROM users WHERE email = ?",
      [$email]
    );

    if (empty($users)) {
      return null;
    }

    return User::fromArray($users[0]);
  }

  private function getUserByUsername(string $username): ?User
  {
    $users = $this->userRepository->execute(
      "SELECT * FROM users WHERE username = ?",
      [$username]
    );

    if (empty($users)) {
      return null;
    }

    return User::fromArray($users[0]);
  }

  private function getBaseUrl(): string
  {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
  }
}
