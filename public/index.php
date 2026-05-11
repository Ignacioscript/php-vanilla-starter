<?php

// this is to avoid bad var types
declare(strict_types=1);

// imports  without them you must use the whole path to create an instance or use it
use App\Core\Container;
use App\Core\EnvironmentLoader;
use App\Core\Renderer;
use App\Http\Request;
use App\Http\Router;
use App\Services\PokemonService;

// manual loads to this files to be able to use their logic
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/app/Core/helpers.php';

 // assign the root path to a var
$basePath = dirname(__DIR__ );


//  loads the configuration files and setup app data and databsse data
$envLoader = new EnvironmentLoader();
if (file_exists($basePath . '/.env')) {
    $envLoader->load($basePath . '/.env');
}

// return an array of configuration data
$env = $envLoader->all();

// array of configuration data
$config = [
    'app' => require $basePath . '/config/app.php',
    'database' => require $basePath . '/config/database.php',
];

// container logic to inject classes objects or closures
$container = new Container();
$container->set('config', $config);
$container->set(Renderer::class, fn () => new Renderer($basePath . '/resources/views'));
$container->set(PokemonService::class, fn () => new PokemonService($config['app']['pokeapi_base_url']));

$request = Request::capture();

$router = new Router($container);
$router->get('/pokemon/{name}', [App\Http\Controllers\PokemonController::class, 'show']);

$response = $router->dispatch($request);
$response->send();
