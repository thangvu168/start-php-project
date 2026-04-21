<?php

class AuthMiddleware implements Middleware
{
    public function handle(callable $next)
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true || !isset($_SESSION['user_id'])) {
            header('Location: login');
            exit;
        }

        return $next();
    }
}
