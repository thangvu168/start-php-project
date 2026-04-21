<?php

class View
{
    protected static string $_BASE_PATH = __DIR__ . '/../../app/Views/';

    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = self::$_BASE_PATH . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "View not found: {$view}";
            return;
        }

        if (!isset($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        if (!isset($data['csrf_token'])) {
            $data['csrf_token'] = $_SESSION['_csrf_token'];
        }

        // Extract is used to convert array keys into variables
        /**
         * Example:
         * $data = ['name' => 'John', 'age' => 30];
         * extract($data);
         * echo $name; // Output: John
         * echo $age; // Output: 30
         */
        extract($data);

        // Render view vào buffer
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render layout
        $layoutFile = self::$_BASE_PATH . "layouts/{$layout}.php";

        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }

        require $layoutFile;
    }

    public static function renderError(int $code, array $data = []): void
    {
        http_response_code($code);

        $errorFile = self::$_BASE_PATH . "{$code}.php";

        if (file_exists($errorFile)) {
            // Conver array to var
            extract($data);

            // Turn on output buffering
            ob_start();
            require $errorFile;
            $content = ob_get_clean();
            echo $content;
            return;
        }

        // Fallback
        echo "{$code} - Error occurred";
    }
}
