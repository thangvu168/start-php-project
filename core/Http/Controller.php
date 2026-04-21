<?php

class Controller
{
  protected function view(string $view, array $data = []): void
  {
    // Extract is used to convert array keys into variables
    /**
     * Example:
     * $data = ['name' => 'John', 'age' => 30];
     * extract($data);
     * echo $name; // Output: John
     * echo $age; // Output: 30
     */
    extract($data);
    $filePath = __DIR__ . "/../../app/Views/{$view}.php";
    if (!file_exists($filePath)) {
      http_response_code(404);
      echo "View not found: {$view}";
      return;
    }
    require_once __DIR__ . "/../../app/Views/{$view}.php";
  }

  protected function redirect(string $path): void
  {
    header("Location: {$path}");
    exit;
  }
}
