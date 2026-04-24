<?php

function config($key = null)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../config.php';
    }

    if ($key === null) {
        return $config;
    }

    // Support dot notation
    if (str_contains($key, '.')) {
        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    return $config[$key] ?? null;
}

function getClientIp(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    return trim($ip);
}


function getLoginAttempts(string $ip): int
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    $key = "attempts_{$ip}";
    $timeKey = "time_{$ip}";

    // Kiểm tra nếu đã quá 1 giờ kể từ lần ghi nhận đầu tiên -> reset
    if (isset($_SESSION['login_attempts'][$timeKey])) {
        if (time() - $_SESSION['login_attempts'][$timeKey] > 3600) {
            unset($_SESSION['login_attempts'][$key]);
            unset($_SESSION['login_attempts'][$timeKey]);
            return 0;
        }
    }

    return $_SESSION['login_attempts'][$key] ?? 0;
}

function incrementLoginAttempts(string $ip): int
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    $key = "attempts_{$ip}";
    $timeKey = "time_{$ip}";

    $_SESSION['login_attempts'][$key] = (getLoginAttempts($ip) + 1);
    $_SESSION['login_attempts'][$timeKey] = time();

    return $_SESSION['login_attempts'][$key];
}

function resetLoginAttempts(string $ip): void
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    $key = "attempts_{$ip}";
    $timeKey = "time_{$ip}";

    unset($_SESSION['login_attempts'][$key]);
    unset($_SESSION['login_attempts'][$timeKey]);
}

function needsCaptcha(string $ip): bool
{
    return getLoginAttempts($ip) >= 3;
}

// Sanitize output to prevent XSS
function e($string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
