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

        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isJsonRequest = strtolower($requestedWith) === 'xmlhttprequest' || str_contains($accept, 'application/json');

        if ($isJsonRequest) {
            header('Content-Type: application/json; charset=utf-8');

            $message = $statusCode >= 500 ? 'Lỗi máy chủ' : $exception->getMessage();


            error_log('[EXCEPTION] ' . json_encode([
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]));


            echo json_encode([
                'success' => false,
                'message' => $message,
                'errors' => []
            ]);
            return;
        }

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
