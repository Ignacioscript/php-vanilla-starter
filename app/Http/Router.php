<?php

declare(strict_types=1);

namespace App\Http;

use App\Core\Container;

final class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = compact('method', 'path', 'pattern', 'handler');
    }

    public function dispatch(Request $request): Response
    {
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            if (!preg_match($route['pattern'], $request->uri, $matches)) {
                continue;
            }

            if ($route['method'] !== $request->method) {
                $allowedMethods[] = $route['method'];
                continue;
            }

            $params = array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);

            return $this->invokeHandler($route['handler'], $params);
        }

        if ($allowedMethods !== []) {
            return new Response('Method Not Allowed', 405, ['Allow' => implode(', ', $allowedMethods)]);
        }

        return new Response('Not Found', 404);
    }

    private function invokeHandler(callable|array $handler, array $params): Response
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = $this->container->get($class);
            return $instance->{$method}(...array_values($params));
        }

        return $handler(...array_values($params));
    }
}
