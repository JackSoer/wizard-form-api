<?php

declare (strict_types = 1);

require __DIR__ . '/App/Router.php';
require __DIR__ . '/App/Controllers/UserController.php';
require __DIR__ . '/App/App.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: *");
header("Content-Type: application/json");

use App\App;
use App\Router;

$router = new Router();

$router
    ->get('/users', [UserController::class, 'getUsers'])
    ->post('/users', [UserController::class, 'store']);

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
))->run();
