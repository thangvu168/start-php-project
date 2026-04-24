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
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}


// Sanitize output to prevent XSS
function e($string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
