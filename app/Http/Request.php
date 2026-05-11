<?php

declare(strict_types=1);

namespace App\Http;

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array $headers,
        public readonly array $query,
        public readonly array $parsedBody,
    ) {
    }

    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?'); // strtok -> split at token: in this case '?' /pokemon/pikachu?type=electric = /pokemon/pikachu and remove the query part

        return new self(
            $method,
            $uri,
            self::headers(),
            $_GET ?? [],
            $_POST ?? []
        );
    }

    private static function headers(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = $value;
            }
        }
        return $headers;
    }
}
