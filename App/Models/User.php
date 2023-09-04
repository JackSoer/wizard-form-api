<?php

require __DIR__ . "/DB.php";

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB(parse_ini_file(__DIR__ . '/../../.env'));
    }

    // Save a user in a database
    public function store($request, $filesRequest)
    {
        $user = static::getUser($request, $filesRequest);
        $avatarPath = null;

        if ($user['avatar']['tmp_name'] !== '') {
            $avatarPath = static::uploadAvatar($user['avatar']);
        }

        $query = "INSERT INTO users (name, email, avatar, password) VALUES (:name, :email, :avatar, :password)";
        $params = [
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $avatarPath,
            'password' => password_hash($user['password'], PASSWORD_DEFAULT),
        ];
        $stmt = $this->db->pdo->prepare($query);

        try {
            $stmt->execute($params);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    // Save avatar into a server
    public static function uploadAvatar($avatar)
    {
        $storagePath = __DIR__ . '/../../storage';

        Validator::validateFile($avatar, ['image/jpeg', 'image/png'], 1);

        if (!empty($_SESSION['validation'])) {
            Request::redirect("../../views/register.php");
        }

        $filePath = FileManager::uploadFile($avatar, $storagePath);

        return $filePath;
    }

    // Extract and get user data from request
    public static function getUser($request, $filesRequest)
    {
        $user = [];

        $user['name'] = $request['name'] ?? null;
        $user['email'] = $request['email'] ?? null;
        $user['password'] = $request['password'] ?? null;
        $user['passwordConfirmation'] = $request['password_confirmation'] ?? null;
        $user['avatar'] = $filesRequest['avatar'] ?? null;

        return $user;
    }
}
