<?php

class Router
{
  private array $routes = [];

  /**
   * Action: [ControllerName, MethodName]
   */

  public function get(string $path, array $action): Route
  {
    return $this->addRoute('get', $path, $action);
  }

  public function post(string $path, array $action): Route
  {
    return $this->addRoute('post', $path, $action);
  }

  public function put(string $path, array $action): Route
  {
    return $this->addRoute('put', $path, $action);
  }

  private function addRoute(string $method, string $path, array $action): Route
  {
    $route = new Route($method, $path, $action);
    $this->routes[$method][$path] = $route;
    return $route;
  }

  public function dispatch(string $method, string $uri): void
  {
    $method = strtolower($method);
    $path = parse_url($uri, PHP_URL_PATH);

    // Check route exists
    if (isset($this->routes[$method][$path])) {
      $route = $this->routes[$method][$path];
      $action = $route->action;
      $controllerName = $action[0];
      $methodName = $action[1];

      if (!class_exists($controllerName)) {
        throw new HttpException("Controller not found", 500);
      }

      if (!method_exists($controllerName, $methodName)) {
        throw new HttpException("Action not found", 500);
      }

      $controller = new $controllerName();
      $controllerAction = fn() => $controller->$methodName();
      $pipeline = $this->buildPipelineMiddleware($route->middlewares, $controllerAction);
      $pipeline();
    } else {
      throw new HttpException("Not found", 404);
    }
  }

  private function buildPipelineMiddleware(array $middlewares, callable $cb): callable
  {
    $next = $cb;
    foreach (array_reverse($middlewares) as $middlewareClass) {
      // $middleware = new $middlewareClass();
      $middleware = $this->resolveMiddleware($middlewareClass);

      // Closure - Anonymous function
      // use - Capture variable
      $next = function () use ($middleware, $next) {
        return $middleware->handle($next);
      };
    }

    return $next;
  }

  private function resolveMiddleware(string $class)
  {
    $db = Database::getConnection();

    // Inject dependency vào từng middleware
    $factories = [
      AuthMiddleware::class => fn() => new AuthMiddleware(new RememberTokenRepository($db)),
    ];

    return isset($factories[$class]) ? ($factories[$class])() : new $class();
  }
}
