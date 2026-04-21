<?php

class Controller
{
  protected function view(string $view, array $data = [], string $layout = 'main'): void
  {
    View::render($view, $data, $layout);
  }

  protected function redirect(string $path): void
  {
    header("Location: {$path}");
    exit;
  }
}
