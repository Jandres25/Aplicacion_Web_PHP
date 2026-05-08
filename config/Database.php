<?php

namespace Config;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection()
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        try {
            $dsn = "mysql:host=" . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ";port=" . ($_ENV['DB_PORT'] ?? '3306') . ";dbname=" . ($_ENV['DB_DATABASE'] ?? '') . ";charset=utf8mb4";

            self::$connection = new PDO($dsn, $_ENV['DB_USERNAME'] ?? '', $_ENV['DB_PASSWORD'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

        return self::$connection;
    }

    private function __clone() {}

    public function __wakeup(): never
    {
        throw new Exception('No se puede deserializar una instancia de Database');
    }
}
