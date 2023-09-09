<?php

declare (strict_types = 1);

require __DIR__ . "/../vendor/autoload.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: *");
header("Content-Type: application/json");

use Api\App;
use Api\Controllers\UserController;
use Api\Models\User;
use Api\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$router = new Router();

$userModel = new User();
$users = $userModel->getUsers();

$router
    ->get('/api/users', [UserController::class, 'getUsers'])
    ->post('/api/users', [UserController::class, 'update'], $users)
    ->post('/api/users', [UserController::class, 'store']);

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
))->run();
