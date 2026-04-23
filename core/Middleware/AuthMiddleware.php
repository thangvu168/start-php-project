<?php

class AuthMiddleware implements Middleware
{

    public function __construct(
        private RememberTokenRepository $rememberTokenRepository,
    ) {}

    public function handle(callable $next)
    {
        error_log("AuthMiddleware: Checking authentication for request URI: {$_SERVER['REQUEST_URI']}");
        if (!empty($_SESSION['user_id'])) {
            error_log("User is authenticated via session: user_id={$_SESSION['user_id']}");
            return $next();
        }

        if (!empty($_COOKIE['remember_token'])) {
            $token = hash('sha256', $_COOKIE['remember_token']);

            error_log("Checking remember token: token={$token}");

            $record = $this->rememberTokenRepository->findValidToken($token);

            if ($record && strtotime($record['expires_at']) > time()) {
                $_SESSION['user_id'] = $record['user_id'];
                $_SESSION['logged_in'] = true;

                return $next();
            }
        }

        return $this->unauthorized();
    }

    private function unauthorized()
    {
        header('Location: /login');
        exit;
    }
}
