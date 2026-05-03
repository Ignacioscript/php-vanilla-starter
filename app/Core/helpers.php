<?php

declare(strict_types=1);

use App\Http\Response;

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function jsonResponse(array $data, int $status = 200): Response
{
    return new Response(
        json_encode($data, JSON_THROW_ON_ERROR),
        $status,
        ['Content-Type' => 'application/json']
    );
}
