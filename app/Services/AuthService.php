<?php

class AuthService
{
  public function __construct(
    private UserRepository $userRepository,
    private PasswordResetRepository $passwordResetRepository
  ) {}

  public function login(string $email, string $password): ?User
  {
    $user = $this->getUserByEmail($email);

    if (!$user) {
      return null;
    }

    if (!password_verify($password, $user->password)) {
      return null;
    }

    return $user;
  }

  public function createResetToken(string $email): ?string
  {
    $user = $this->getUserByEmail($email);
    if (!$user) return null;

    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', time() + 3600);

    $this->passwordResetRepository->create([
      'email' => $email,
      'token_hash' => $hash,
      'expires_at' => $expires,
    ]);

    return $token;
  }

  public function verifyToken(string $token): ?int
  {
    if (empty($token)) return null;
    $hash = hash('sha256', $token);
    $data = $this->passwordResetRepository->getAll(
      filters: [
        ['column' => 'token_hash', 'op' => '=', 'value' => $hash],
      ],
      limit: 1
    );
    if (empty($data)) return null;
    $record = $data[0];
    if (strtotime($record['expires_at']) < time()) return null;

    $email = $record['email'];
    $users = $this->userRepository->execute(
      "SELECT id FROM users WHERE email = ?",
      [$email]
    );
    if (empty($users)) return null;
    return $users[0]['id'];
  }

  public function consumeToken(string $token): void
  {
    $hash = hash('sha256', $token);
    $this->passwordResetRepository->execute(
      'DELETE FROM password_resets WHERE token_hash = ?',
      [$hash]
    );
  }

  public function verifyRecaptcha(string $response): bool
  {
    if (empty(config('recaptcha.secret'))) return true;
    if (empty($response)) return false;

    $post = http_build_query([
      'secret' => config('recaptcha.secret'),
      'response' => $response,
      'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);

    $opts = ['http' => ['method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n", 'content' => $post]];
    $context = stream_context_create($opts);
    $res = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    if (!$res) return false;
    $data = json_decode($res, true);
    return !empty($data['success']);
  }

  public function register(array $data): int
  {
    // Check if email already exists
    if ($this->getUserByEmail($data['email'])) {
      throw new Exception("Email đã tồn tại");
    }

    if ($this->getUserByUsername($data['username'])) {
      throw new Exception("Tên đăng nhập đã tồn tại");
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
}
