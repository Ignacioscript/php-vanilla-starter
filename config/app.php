<?php

declare(strict_types=1);

return [
    'name' => 'PHP Vanilla Starter',
    'env' => $env['APP_ENV'] ?? 'local',
    'debug' => $env['APP_DEBUG'] ?? true,
    'url' => $env['APP_URL'] ?? 'http://localhost:8000',
    'pokeapi_base_url' => $env['POKEAPI_BASE_URL'] ?? 'https://pokeapi.co/api/v2',
];
