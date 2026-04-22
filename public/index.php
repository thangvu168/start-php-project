<?php

session_start();

if (!isset($_SESSION['_csrf_token'])) {
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../core/bootstrap.php';
require_once __DIR__ . '/../app/bootstrap.php';

ErrorHandler::register();

$db = Database::getConnection();

$router = new Router();
require_once __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
