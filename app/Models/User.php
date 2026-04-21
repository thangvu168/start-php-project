<?php

class User extends BaseModel
{
  public function __construct(
    int $id,
    public string $name,
    public string $firstName,
    public string $lastName,
    public string $username,
    public string $email,
    public string $password,
    public ?string $avatar,
    public ?string $regDate = null,
    ?string $createdAt = null,
    ?string $updatedAt = null,
  ) {
    parent::__construct(
      id: $id,
      createdAt: $createdAt ?? $regDate,
      updatedAt: $updatedAt,
    );

    $this->regDate = $regDate ?? $this->createdAt;
  }

  // Return an instance of User from an associative array
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'],
      name: $data['name'],
      firstName: $data['first_name'],
      lastName: $data['last_name'],
      username: $data['username'],
      email: $data['email'],
      password: $data['password'],
      avatar: $data['avatar'] ?? null,
      createdAt: self::resolveCreatedAt($data),
      updatedAt: self::resolveUpdatedAt($data),
    );
  }

  // Convert the User instance back to an associative array
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'first_name' => $this->firstName,
      'last_name' => $this->lastName,
      'username' => $this->username,
      'email' => $this->email,
      'password' => $this->password,
      'avatar' => $this->avatar,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }
}
