<?php

declare (strict_types = 1);

namespace Api\Models;

use Api\Models\DB;
use Api\Request;
use Api\Utils\FileManager;
use Exception;

class User
{
    protected $db;

    public function __construct()
    {
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

        $params = Request::getStoreParams($user, ["company", "position", "aboutMe"]);
        $params['photo'] = $avatarPath ?? null;

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

        // Upload new avatar
        $avatarPath = null;

        if (isset($user['photo'])) {
            try {
                $avatarPath = static::uploadAvatar($user['photo']);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $query = "UPDATE users SET first_name=:first_name, birthdate=:birthdate, report_subject=:report_subject, country=:country, phone=:phone, email=:email, last_name=:last_name, company=:company, position=:position, about_me=:about_me, photo=:photo WHERE id=:id";

        $stmt = $this->db->pdo->prepare($query);

        $params = Request::getPatchParams($user, $oldUser);
        $params['photo'] = $avatarPath ?? $oldUser['photo'];

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
