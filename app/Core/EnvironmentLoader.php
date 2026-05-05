<?php

declare(strict_types=1);

namespace App\Core;

final class EnvironmentLoader
{
    // This array will store the environment variables.
    private array $vars = [];

    // This function loads the environment variables from a .env file.
    public function load(string $path): void
    {
        // Read the file into an array, ignoring new lines and skipping empty lines.
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // If the file cannot be read, throw an exception.
        if ($lines === false) {
            throw new \RuntimeException("Unable to read env file: {$path}");
        }

        // Iterate over each line of the file.
        foreach ($lines as $line) {
            // Trim whitespace from the beginning and end of the line.
            $line = trim($line);
            // If the line is empty or starts with a '#', skip it.
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Split the line into a key and a value.
            [$key, $value] = explode('=', $line, 2) + [null, null];
            // If the key or the value is null, skip it.
            if ($key === null || $value === null) {
                continue;
            }

            // Store the key and the value in the $vars array.
            $this->vars[trim($key)] = $this->cast(trim($value));
        }
    }

    // This function returns all the loaded environment variables.
    public function all(): array
    {
        return $this->vars;
    }

    // This function casts the value to the correct type.
    private function cast(string $value): mixed
    {
        // Normalize the value to lowercase.
        $normalized = strtolower($value);

        // Return the correct type based on the value.
        return match ($normalized) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => trim($value, "\"'"),
        };
    }
}
