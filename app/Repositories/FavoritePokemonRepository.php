<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class FavoritePokemonRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function saveFavorite(int $userId, int $pokemonId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO favorite_pokemon (user_id, pokemon_id) VALUES (:user_id, :pokemon_id)'
        );

        return $stmt->execute([
            'user_id' => $userId,
            'pokemon_id' => $pokemonId,
        ]);
    }
}
