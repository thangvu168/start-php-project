<?php

class Controller
{
  protected function view(string $view, array $data = [], string $layout = 'main'): void
  {
    View::render($view, $data, $layout);
  }

  protected function json(array $data, int $statusCode = 200): void
  {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
  }

  protected function isJsonRequest(): bool
  {
    $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';

    return strtolower($requestedWith) === 'xmlhttprequest' || str_contains($accept, 'application/json');
  }

  protected function validateCsrfToken(): void
  {
    $sessionToken = $_SESSION['_csrf_token'] ?? '';
    $requestToken = $_POST['_csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

    if (!is_string($sessionToken) || !is_string($requestToken) || $sessionToken === '' || !hash_equals($sessionToken, $requestToken)) {
      if ($this->isJsonRequest()) {
        $this->json([
          'success' => false,
          'message' => 'Invalid CSRF token',
          'errors' => [],
          'redirect' => '/login',
        ], 419);
      }

      throw new HttpException('Invalid CSRF token', 419);
    }
  }

  protected function redirect(string $path): void
  {
    header("Location: {$path}");
    exit;
  }
}
