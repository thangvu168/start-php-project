<?php

return [
    'db' => [
        'host' => $_ENV['DB_SERVER'] ?? '127.0.0.1',
        'username' => $_ENV['DB_USERNAME'] ?? '',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'database' => $_ENV['DB_NAME'] ?? '',
    ],

    'mail' => [
        'host' => $_ENV['MAILER_HOST'] ?? '',
        'port' => $_ENV['MAILER_PORT'] ?? 587,
        'username' => $_ENV['MAILER_USERNAME'] ?? '',
        'password' => $_ENV['MAILER_PASSWORD'] ?? '',
        'from' => $_ENV['MAILER_FROM'] ?? '',
        'from_name' => $_ENV['MAILER_FROM_NAME'] ?? '',
    ],

    'recaptcha' => [
        'site_key' => $_ENV['RECAPTCHA_SITEKEY'] ?? '',
        'secret' => $_ENV['RECAPTCHA_SECRET'] ?? '',
    ],
];
