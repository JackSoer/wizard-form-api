<?php

declare (strict_types = 1);

use App\Models\User\User;
use App\Utils\Validator;

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
            $camelCaseUsers = static::transformUsersToCamelCase($users);

            http_response_code(200);

            return json_encode([
                "users" => $camelCaseUsers,
                "status" => 200,
            ]);
        } catch (\Exception $e) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    // Save a user in database
    public static function store()
    {
        $user = static::getUser($_POST, $_FILES);

        $errors = static::userValidation($user, 'post');

        if (!empty($errors)) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "errors" => $errors,
            ]);
        }

        $userModel = new User();

        try {
            $newUserId = $userModel->store($user);

            http_response_code(201);

            return json_encode([
                "status" => 201,
                "userId" => $newUserId,
            ]);
        } catch (\Exception $e) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    // Update user
    public static function update(int $id)
    {
        $user = static::getUser($_POST, $_FILES);

        $errors = static::userValidation($user, 'patch', $id);

        if (!empty($errors)) {
            http_response_code(400);

            return json_encode([
                "status" => 400,
                "errors" => $errors,
            ]);
        }

        $userModel = new User();

        try {
            $newUserId = $userModel->update($id, $user);

            http_response_code(201);

            return json_encode([
                "status" => 201,
                "userId" => $newUserId,
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
    public static function userValidation(array $user, string $method, int $id = null)
    {
        $errors = [];

        // Validate fields
        if ($method === 'post') {
            if (strlen($user["firstName"]) < 2) {
                array_push($errors, "First name is require and min lenght is 2");
            }
            if (strlen($user["lastName"]) < 2) {
                array_push($errors, "Last name is required and min lenght is 2");
            }
            if (!preg_match("/^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}$/", $user['phone'])) {
                array_push($errors, "Phone should be in (xxx) xxx-xxxx format");
            }
            if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (!preg_match("%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%", $user['birthdate'])) {
                array_push($errors, "Birthdate is not valid");
            }
            if (!trim($user["country"])) {
                array_push($errors, "Country is required");
            }
            if ((strlen($user["reportSubject"])) < 5) {
                array_push($errors, "Report subject is required and min lenght is 5");
            }
        }

        // Validate a photo
        if (isset($user['photo']) && !Validator::validateFile($user['photo'], ['image/jpeg', 'image/png'], 1)) {
            array_push($errors, "File size too big or incorrect file type");
        }

        $userModel = new User();

        // Validate email duplicates
        $duplicateByEmail = $userModel->getUserByEmail($user['email']);

        if ($method === 'post') {
            if ($duplicateByEmail) {
                array_push($errors, "Email already taken");
            }
        } else if ($method === 'patch') {
            if (isset($user['email'])) {
                if ($duplicateByEmail && $id !== $duplicateByEmail['id']) {
                    array_push($errors, "Email already taken");
                }
            }
        }

        return $errors;
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

    // Transform to json standart - camelCase
    public static function transformUsersToCamelCase(array $users)
    {
        $newUsers = [];

        foreach ($users as $user) {
            $user['reportSubject'] = $user['report_subject'] ?? null;
            $user['lastName'] = $user['last_name'] ?? null;
            $user['firstName'] = $user['first_name'] ?? null;
            $user['aboutMe'] = $user['about_me'] ?? null;

            unset($user['report_subject']);
            unset($user['about_me']);
            unset($user['last_name']);
            unset($user['first_name']);

            array_push($newUsers, $user);
        }

        return $newUsers;
    }
}
