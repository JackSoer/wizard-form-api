<?php

declare (strict_types = 1);

namespace Api\Models;

use Api\Models\DB;
use Api\Utils\FileManager;
use Dotenv\Dotenv;
use Exception;

class User
{
    protected $db;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->db = new DB($_ENV);
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

    public function getUserByEmail(string $email)
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

    public function getUserById(int $id)
    {
        $query = "SELECT * FROM `users` WHERE id=:id";

        $params = [
            'id' => $id,
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

    // Update user
    public function update(int $id, array $user)
    {
        $oldUser = $this->getUserById($id);

        // Delete old avatar
        if (isset($user['photo'])) {
            $filePath = __DIR__ . '/../../' . $oldUser['photo'];

            if (is_file(($filePath))) {
                unlink($filePath);
            }
        }

        $avatarPath = null;

        if (isset($user['photo'])) {
            try {
                $avatarPath = static::uploadAvatar($user['photo']);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $query = "UPDATE users SET first_name=:first_name, birthdate=:birthdate, report_subject=:report_subject, country=:country, phone=:phone, email=:email, last_name=:last_name, company=:company, position=:position, about_me=:about_me, photo=:photo WHERE id=:id";
        $params = [
            'first_name' => $user['firstName'] ?? $oldUser['first_name'],
            'birthdate' => $user['birthdate'] ?? $oldUser['birthdate'],
            'report_subject' => $user['reportSubject'] ?? $oldUser['report_subject'],
            'country' => $user['country'] ?? $oldUser['country'],
            'phone' => $user['phone'] ?? $oldUser['phone'],
            'email' => $user['email'] ?? $oldUser['email'],
            'last_name' => $user['lastName'] ?? $oldUser['last_name'],
            'company' => $user['company'] ?? $oldUser['company'],
            'position' => $user['position'] ?? $oldUser['position'],
            'about_me' => $user['aboutMe'] ?? $oldUser['about_me'],
            'photo' => $avatarPath ?? $oldUser['photo'],
            'id' => $id,
        ];

        $stmt = $this->db->pdo->prepare($query);

        try {
            $stmt->execute($params);

            return $id;
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
