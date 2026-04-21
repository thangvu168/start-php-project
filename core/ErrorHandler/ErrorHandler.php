<?php

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(Throwable $exception): void
    {
        $statusCode = 500;

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
        }


        http_response_code($statusCode);

        self::renderErrorView($statusCode, $exception);
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    private static function renderErrorView(int $code, Throwable $e)
    {
        View::renderError($code);
    }
}
