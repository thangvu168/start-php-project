<?php

interface Middleware
{
    public function handle(callable $next);
}
