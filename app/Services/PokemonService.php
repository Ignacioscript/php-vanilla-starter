<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\PokemonDTO;

final class PokemonService
{
    public function __construct(private string $baseUrl)
    {
    }

    public function getPokemon(string $name): ?PokemonDTO
    {
        $cleanName = $name |> strtolower(...); // PHP 8.5 pipe operator

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'header' => [
                    'User-Agent: php-vanilla-starter',
                    'Accept: application/json',
                ],
            ],
        ]);

        $url = rtrim($this->baseUrl, '/') . '/pokemon/' . rawurlencode($cleanName);
        $json = @file_get_contents($url, false, $context);

        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new PokemonDTO(
            id: (int) $data['id'],
            name: (string) $data['name'],
            height: (int) $data['height'],
            weight: (int) $data['weight'],
            types: array_map(fn ($type) => $type['type']['name'], $data['types'] ?? []),
            sprites: $data['sprites'] ?? []
        );
    }
}
