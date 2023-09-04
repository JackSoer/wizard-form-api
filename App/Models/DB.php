<?php

class DB
{
    public PDO $pdo;

    public function __construct($config)
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $config["DB_HOST"] . ";dbname=" . $config["DB_NAME"], $config["DB_USERNAME"], $config["DB_PASSWORD"]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
