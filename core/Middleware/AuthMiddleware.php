<?php

class AuthMiddleware implements Middleware
{
    public function handle(callable $next)
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
            $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
            $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
            $isJsonRequest = strtolower($requestedWith) === 'xmlhttprequest' || str_contains($accept, 'application/json');

            if ($isJsonRequest) {
                http_response_code(401);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'errors' => [],
                    'redirect' => '/login',
                ]);
                exit;
            }

            header('Location: /login');
            exit;
        }

        return $next();
    }
}
