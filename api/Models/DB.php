<?php

declare (strict_types = 1);

namespace Api\Models;

use PDO;

class DB
{
    public PDO $pdo;

    public function __construct($config)
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $config["DB_HOST"] . ";dbname=" . $config["DB_NAME"], $config["DB_USERNAME"], $config["DB_PASSWORD"]);

            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
