<?php

class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public array $action,
        public array $middlewares = []
    ) {}

    public function middleware(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }
}
