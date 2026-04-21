<?php

class BaseModel
{
  public function __construct(
    public int $id,
    public ?string $createdAt = null,
    public ?string $updatedAt = null,
  ) {}

  protected static function resolveCreatedAt(array $data): ?string
  {
    return $data['created_at'] ?? $data['reg_date'] ?? null;
  }

  protected static function resolveUpdatedAt(array $data): ?string
  {
    return $data['updated_at'] ?? null;
  }
}
