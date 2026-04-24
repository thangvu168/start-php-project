<?php

class LoginAttemptRepository extends Repository
{
    protected string $table = 'login_attempts';
    protected array $allowedColumns = ['ip', 'attempts', 'first_attempt_at', 'updated_at'];

    public function findByIp(string $ip): ?array
    {
        return $this->getAll(
            filters: [['column' => 'ip', 'op' => '=', 'value' => $ip]],
            limit: 1
        )[0] ?? null;
    }

    public function upsertIncrement(string $ip): void
    {
        $now = date('Y-m-d H:i:s');
        $this->execute(
            'INSERT INTO login_attempts (ip, attempts, first_attempt_at, updated_at)
             VALUES (?, 1, ?, ?)
             ON DUPLICATE KEY UPDATE attempts = attempts + 1, updated_at = ?',
            [$ip, $now, $now, $now]
        );
    }

    public function deleteByIp(string $ip): void
    {
        $this->execute('DELETE FROM login_attempts WHERE ip = ?', [$ip]);
    }
}
