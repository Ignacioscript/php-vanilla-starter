<?php

declare(strict_types=1);

namespace App\DTO;

final class PokemonDTO
{
    public function __construct(
        public private(set) int $id,
        public private(set) string $name,
        public private(set) int $height,
        public private(set) int $weight,
        public private(set) array $types,
        public private(set) array $sprites,
    ) {
    }

    public string $displayName {
        get => ucfirst($this->name);
    }
}
