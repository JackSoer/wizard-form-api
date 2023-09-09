<?php

declare (strict_types = 1);

require __DIR__ . '/Router.php';
require __DIR__ . '/Controllers/UserController.php';
require __DIR__ . '/App.php';
require __DIR__ . '/Models/User.php';
require __DIR__ . '/Utils/Validator.php';
require __DIR__ . '/Utils/FileManager.php';
require __DIR__ . '/Exceptions/RouteNotFoundException.php';
require __DIR__ . "/Models/DB.php";
require __DIR__ . "/../vendor/vlucas/phpdotenv/src/Dotenv.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: *");
header("Content-Type: application/json");

use Api\App;
use Api\Controllers\UserController;
use Api\Models\User;
use Api\Router;

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
