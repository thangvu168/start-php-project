<?php


$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/', [DashboardController::class, 'showDashboard'])->middleware([
    AuthMiddleware::class
]);

$router->get('/profile', [UserController::class, 'showProfile'])->middleware([
    AuthMiddleware::class
]);

$router->post('/profile', [UserController::class, 'changeProfile'])->middleware([
    AuthMiddleware::class
]);
