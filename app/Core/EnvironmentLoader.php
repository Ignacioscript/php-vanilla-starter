<?php

declare(strict_types=1);

namespace App\Core;

final class EnvironmentLoader
{
    private array $vars = [];

    public function load(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new \RuntimeException("Unable to read env file: {$path}");
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2) + [null, null];
            if ($key === null || $value === null) {
                continue;
            }

            $this->vars[trim($key)] = $this->cast(trim($value));
        }
    }

    public function all(): array
    {
        return $this->vars;
    }

    private function cast(string $value): mixed
    {
        $normalized = strtolower($value);

        return match ($normalized) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => trim($value, "\"'"),
        };
    }
}
