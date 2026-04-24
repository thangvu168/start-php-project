<?php

class PasswordResetRepository extends Repository
{
    protected string $table = 'password_resets';
    protected array $allowedColumns = [
        'id', 'email', 'token_hash', 'expires_at', 'created_at',
    ];
}
