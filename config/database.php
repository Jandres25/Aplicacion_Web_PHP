<?php

namespace Config;

require_once __DIR__ . '/../core/Env.php';

use Core\Env;
use PDO;

class Database
{
    public static function getConnection()
    {
        $host = Env::get('DB_HOST', '127.0.0.1');
        $port = Env::get('DB_PORT', '3306');
        $database = Env::get('DB_DATABASE', 'app');
        $username = Env::get('DB_USERNAME', 'root');
        $password = Env::get('DB_PASSWORD', 'root');

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}
