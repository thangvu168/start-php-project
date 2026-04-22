<?php

class AuthService
{
  public function __construct(
    private UserRepository $userRepository,
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
