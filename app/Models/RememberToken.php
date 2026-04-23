<?php

class RememberToken extends BaseModel
{
    public function __construct(
        int $id,
        public int $userId,
        public string $token,
        public string $expiresAt,
        ?string $createdAt = null,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            userId: $data['user_id'],
            token: $data['token'],
            expiresAt: $data['expires_at'],
            createdAt: $data['created_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'token' => $this->token,
            'expires_at' => $this->expiresAt,
            'created_at' => $this->createdAt,
        ];
    }
}
