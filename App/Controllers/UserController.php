<?php

class UserController
{
    public function __construct()
    {

    }

    // Save a user in database
    public static function store()
    {
        $user = new User();

        $user->store($_POST, $_FILES);
    }

    // Validate user fields
    public static function userValidation()
    {
        $user = User::getUser($_POST, $_FILES);

        Validator::validateEmptyField($user['name'], 'name', 'Name is required');
        Validator::validateEmail($user['email']);
        Validator::validateEmptyField($user['password'], 'password', 'Password is reqired');
        Validator::validateConfirmPassword($user['password'], $user['passwordConfirmation']);

        if (!empty($_SESSION['validation'])) {
            Request::redirect("../../views/register.php");
        }
    }
}
