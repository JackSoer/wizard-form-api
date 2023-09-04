<?php

require dirname(__DIR__) . '/Utils/Request.php';
require dirname(__DIR__) . '/Utils/Validator.php';
require dirname(__DIR__) . '/Utils/FileManager.php';
require dirname(__DIR__) . '/Controllers/UserController.php';
require dirname(__DIR__) . '/Models/User.php';

// Extract user data from request
$user = User::getUser($_POST, $_FILES);

// Save old values (name and email) for inputs
Request::setOldValue('name', $user['name']);
Request::setOldValue('email', $user['email']);

// Validate user fields
UserController::userValidation();

// Store User
UserController::store();

Request::redirect('../../views/home.php');
