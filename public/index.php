<?php

declare(strict_types=1);

use App\Core\Container;
use App\Core\EnvironmentLoader;
use App\Core\Renderer;
use App\Http\Request;
use App\Http\Router;
use App\Services\PokemonService;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/app/Core/helpers.php';

$basePath = dirname(__DIR__);

$envLoader = new EnvironmentLoader();
if (file_exists($basePath . '/.env')) {
    $envLoader->load($basePath . '/.env');
}

$env = $envLoader->all();

$config = [
    'app' => require $basePath . '/config/app.php',
    'database' => require $basePath . '/config/database.php',
];

$container = new Container();
$container->set('config', $config);
$container->set(Renderer::class, fn () => new Renderer($basePath . '/resources/views'));
$container->set(PokemonService::class, fn () => new PokemonService($config['app']['pokeapi_base_url']));

$request = Request::capture();

$router = new Router($container);
$router->get('/pokemon/{name}', [App\Http\Controllers\PokemonController::class, 'show']);

$response = $router->dispatch($request);
$response->send();
