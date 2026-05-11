<?php

declare(strict_types=1);

return [
    'driver' => $env['DB_DRIVER'] ?? 'mysql',
    'host' => $env['DB_HOST'] ?? '127.0.0.1',
    'port' => $env['DB_PORT'] ?? '3306',
    'database' => $env['DB_NAME'] ?? 'pokedex',
    'username' => $env['DB_USER'] ?? 'root',
    'password' => $env['DB_PASS'] ?? '',
    'charset' => $env['CHAR_SET'] ?? 'utf8mb4',
];
