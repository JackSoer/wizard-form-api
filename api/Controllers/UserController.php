<?php

declare (strict_types = 1);

namespace Api\Controllers;

use Api\Models\User;
use Api\Response;
use Api\Utils\Validator;

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
            $camelCaseUsers = [];
            $photoRoot = 'http://91.107.207.176';

            foreach ($users as $user) {
                $camelCaseUser = Response::transformObjToCamelCase($user);

                if (isset($camelCaseUser['photo'])) {
                    $camelCaseUser['photo'] = $photoRoot . "/" . $camelCaseUser['photo'];
                }

                array_push($camelCaseUsers, $camelCaseUser);
            }

            return Response::jsonResponse(['users' => $camelCaseUsers]);
        } catch (\Exception $e) {
            return Response::jsonResponse(["message" => $e->getMessage()], 400);
        }
    }

    // Save a user in database
    public static function store()
    {
        $user = static::getUser($_POST, $_FILES);

        $errors = static::userValidation($user, 'post');

        if (!empty($errors)) {
            return Response::jsonResponse(["errors" => $errors], 400);
        }

        $userModel = new User();

        try {
            $newUserId = $userModel->store($user);

            return Response::jsonResponse(["userId" => $newUserId], 201);
        } catch (\Exception $e) {
            return Response::jsonResponse(["message" => $e->getMessage()], 400);
        }
    }

    // Update user
    public static function update(int $id)
    {
        $user = static::getUser($_POST, $_FILES);

        $errors = static::userValidation($user, 'patch', $id);

        if (!empty($errors)) {
            return Response::jsonResponse(["errors" => $errors], 400);
        }

        $userModel = new User();

        try {
            $newUserId = $userModel->update($id, $user);

            return Response::jsonResponse(["userId" => $newUserId], 200);
        } catch (\Exception $e) {
            return Response::jsonResponse(["message" => $e->getMessage()], 400);
        }
    }

    // Validate user fields
    public static function userValidation(array $user, string $method, int $id = null)
    {
        $errors = [];

        // Validate fields
        $validPattern = [
            'email' => "/[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$/",
            'birthdate' => "%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%",
        ];

        $validErrors = Validator::validateFields($validPattern, $user);

        if ($method === 'post') {
            $requireFields = ['firstName', 'lastName', 'phone', 'email', 'birthdate', 'country', 'reportSubject'];

            $requireErrors = Validator::validateRequireFields($requireFields, $user);

            $errors = [...$errors, ...$validErrors, ...$requireErrors];
        } else if ($method === 'patch') {
            $errors = [...$errors, ...$validErrors];
        }

        // Validate a photo
        if (isset($user['photo']) && !Validator::validateFile($user['photo'], ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'], 16)) {
            array_push($errors, "File size too big or incorrect file type.Valid types: .jpg, .png, .svg, .webp");
        }

        $userModel = new User();

        // Validate email duplicates
        if (isset($user['email'])) {
            $duplicateByEmail = $userModel->getUserByEmail($user['email']);

            if ($method === 'post' && $duplicateByEmail) {
                array_push($errors, "Email already taken");
            } else if ($method === 'patch' && isset($user['email']) && $duplicateByEmail) {
                $isSamePerson = $id === $duplicateByEmail['id'];

                if (!$isSamePerson) {
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

        foreach ($request as $key => $value) {
            $user[$key] = $value ?? null;
        }

        foreach ($filesRequest as $key => $value) {
            $user[$key] = $value ?? null;
        }

        return $user;
    }
}
