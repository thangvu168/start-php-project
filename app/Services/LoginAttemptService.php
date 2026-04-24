<?php

class LoginAttemptService
{
    private const LOCKOUT_WINDOW = 3600;
    private const CAPTCHA_THRESHOLD = 3;

    public function __construct(private LoginAttemptRepository $repo) {}

    public function getAttempts(string $ip): int
    {
        $row = $this->repo->findByIp($ip);

        if (!$row) {
            return 0;
        }

        if (time() - strtotime($row['first_attempt_at']) > self::LOCKOUT_WINDOW) {
            $this->repo->deleteByIp($ip);
            return 0;
        }

        return (int) $row['attempts'];
    }

    public function increment(string $ip): int
    {
        $this->repo->upsertIncrement($ip);
        return $this->getAttempts($ip);
    }

    public function reset(string $ip): void
    {
        $this->repo->deleteByIp($ip);
    }

    public function needsCaptcha(string $ip): bool
    {
        return $this->getAttempts($ip) >= self::CAPTCHA_THRESHOLD;
    }
}
