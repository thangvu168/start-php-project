<?php

class Router
{
  private array $routes = [];

  /**
   * Action: [ControllerName, MethodName]
   */

  public function get(string $path, array $action): void
  {
    $this->addRoute('get', $path, $action);
  }

  public function post(string $path, array $action): void
  {
    $this->addRoute('post', $path, $action);
  }

  private function addRoute(string $method, string $path, array $action): void
  {
    $this->routes[$method][$path] = $action;
  }

  public function dispatch(string $method, string $uri): void
  {
    $method = strtolower($method);
    $path = parse_url($uri, PHP_URL_PATH);

    // Check route exists
    if (isset($this->routes[$method][$path])) {
      $action = $this->routes[$method][$path];
      $controllerName = $action[0];
      $methodName = $action[1];

      require_once __DIR__ . "/../../app/Controllers/{$controllerName}.php";
      $controller = new $controllerName();
      $controller->$methodName();
    } else {
      throw new HttpException("Not found", 404);
    }
  }
}
