<?php

class RememberTokenRepository extends Repository
{
    protected string $table = 'remember_tokens';

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
