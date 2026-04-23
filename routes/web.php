<?php


$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

// Password reset
$router->get('/password/forgot', [AuthController::class, 'showForgot']);
$router->post('/password/forgot', [AuthController::class, 'sendForgot']);
$router->get('/password/reset', [AuthController::class, 'showReset']);
$router->post('/password/reset', [AuthController::class, 'reset']);

$router->get('/', [DashboardController::class, 'showDashboard'])->middleware([
    AuthMiddleware::class
]);

$router->get('/profile', [UserController::class, 'showProfile'])->middleware([
    AuthMiddleware::class
]);

$router->post('/profile', [UserController::class, 'changeProfile'])->middleware([
    AuthMiddleware::class
]);
