<?php

class User extends BaseModel
{
  public function __construct(
    int $id,
    public string $firstName,
    public string $lastName,
    public string $username,
    public string $email,
    public string $password,
    public ?string $avatar,
    public ?string $phone,
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
      firstName: $data['first_name'],
      lastName: $data['last_name'],
      username: $data['username'],
      email: $data['email'],
      password: $data['password'],
      avatar: $data['avatar'] ?? null,
      phone: $data['phone'] ?? null,
      createdAt: self::resolveCreatedAt($data),
      updatedAt: self::resolveUpdatedAt($data),
    );
  }

  // Convert the User instance back to an associative array
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'first_name' => $this->firstName,
      'last_name' => $this->lastName,
      'username' => $this->username,
      'email' => $this->email,
      'avatar' => $this->avatar,
      'phone' => $this->phone,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }
}
