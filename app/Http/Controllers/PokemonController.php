<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Renderer;
use App\Http\Response;
use App\Services\PokemonService;

final class PokemonController
{
    public function __construct(
        private PokemonService $pokemonService,
        private Renderer $renderer
    ) {
    }

    public function show(string $name): Response
    {
        $pokemon = $this->pokemonService->getPokemon($name);

        if ($pokemon === null) {
            return new Response('Pokemon not found', 404);
        }

        $html = $this->renderer->render('pokemon/show.php', [
            'pokemon' => $pokemon,
        ]);

        $layout = $this->renderer->render('layouts/main.php', [
            'title' => $pokemon->displayName,
            'content' => $html,
        ]);

        return new Response($layout);
    }
}
