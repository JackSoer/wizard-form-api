<?php

declare (strict_types = 1);

namespace App\Models\User;

require __DIR__ . "/DB.php";

use App\Models\DB;
use App\Utils\FileManager;
use Exception;

class User
{
    protected $db;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . '/../../.env');

        $this->db = new DB($config);
    }

    public function getUsers()
    {
        $query = "SELECT * FROM `users`";

        $stmt = $this->db->pdo->prepare($query);

        try {
            $stmt->execute();
            $users = $stmt->fetchAll();

            return $users;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM `users` WHERE email=:email";

        $params = [
            'email' => $email,
        ];

        $stmt = $this->db->pdo->prepare($query);

        try {
            $stmt->execute($params);
            $user = $stmt->fetch();

            return $user;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Save a user in a database
    public function store($user)
    {
        $avatarPath = null;

        if (isset($user['photo'])) {
            try {
                $avatarPath = static::uploadAvatar($user['photo']);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $query = "INSERT INTO users (first_name, birthdate, report_subject, country, phone, email, last_name, company, position, about_me, photo) VALUES (:first_name, :birthdate, :report_subject, :country, :phone, :email, :last_name, :company, :position, :about_me, :photo)";
        $params = [
            'first_name' => $user['firstName'],
            'birthdate' => $user['birthdate'],
            'report_subject' => $user['reportSubject'],
            'country' => $user['country'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'last_name' => $user['lastName'],
            'company' => $user['company'],
            'position' => $user['position'],
            'about_me' => $user['aboutMe'],
            'photo' => $avatarPath,
        ];

        $stmt = $this->db->pdo->prepare($query);

        try {
            $stmt->execute($params);

            return $this->db->pdo->lastInsertId();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Save avatar into a server
    public static function uploadAvatar($avatar)
    {
        $storagePath = __DIR__ . '/../../storage';

        try {
            $filePath = FileManager::uploadFile($avatar, 'avatar', $storagePath);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $filePath;
    }

}
