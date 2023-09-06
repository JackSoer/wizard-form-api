<?php

declare (strict_types = 1);

use App\Models\User\User;
use App\Utils\Validator;

require dirname(__DIR__) . '/Utils/Validator.php';
require dirname(__DIR__) . '/Utils/FileManager.php';
require dirname(__DIR__) . '/Models/User.php';

class UserController
{
    public function __construct()
    {

    }

    // Get all users
    public static function getUsers()
    {
        $userModel = new User();

        try {
            $users = $userModel->getUsers();

            http_response_code(200);

            return json_encode([
                "data" => $users,
                "status" => 200,
            ]);
        } catch (\Exception $e) {
            http_response_code(500);

            return json_encode([
                "status" => 500,
                "message" => $e->getMessage(),
            ]);
        }
    }

    // Save a user in database
    public static function store()
    {
        $user = static::getUser($_POST, $_FILES);

        if (!static::userValidation($user)) {
            http_response_code(402);

            return json_encode([
                "status" => 402,
                "message" => "File size too big or incorrect file type",
            ]);
        }

        $userModel = new User();

        if ($userModel->getUserByEmail($user['email'])) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "message" => "Email already taken",
            ]);
        }

        try {
            $newUserId = $userModel->store($user);

            http_response_code(201);

            return json_encode([
                "status" => 201,
                "data" => $newUserId,
            ]);
        } catch (\Exception $e) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    // Validate user fields
    public static function userValidation($user)
    {
        if (isset($user['photo']) && !Validator::validateFile($user['photo'], ['image/jpeg', 'image/png'], 1)) {
            return false;
        }

        return true;
    }

    // Extract and get user data from request
    public static function getUser($request, $filesRequest)
    {
        $user = [];

        $user['firstName'] = $request['firstName'] ?? null;
        $user['birthdate'] = $request['birthdate'] ?? null;
        $user['reportSubject'] = $request['reportSubject'] ?? null;
        $user['country'] = $request['country'] ?? null;
        $user['photo'] = $filesRequest['photo'] ?? null;
        $user['phone'] = $request['phone'] ?? null;
        $user['email'] = $request['email'] ?? null;
        $user['lastName'] = $request['lastName'] ?? null;
        $user['company'] = $request['company'] ?? null;
        $user['position'] = $request['position'] ?? null;
        $user['aboutMe'] = $request['aboutMe'] ?? null;

        return $user;
    }
}
