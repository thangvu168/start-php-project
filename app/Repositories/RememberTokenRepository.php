<?php

class RememberTokenRepository extends Repository
{
    protected string $table = 'remember_tokens';
    protected array $allowedColumns = [
        'id', 'user_id', 'token', 'expires_at', 'created_at',
    ];

    public function findValidToken(string $token): ?array
    {
        return $this->getAll(
            filters: [
                ['column' => 'token', 'op' => '=', 'value' => $token],
                ['column' => 'expires_at', 'op' => '>', 'value' => date('Y-m-d H:i:s')],
            ],
            limit: 1
        )[0] ?? null;
    }
}
