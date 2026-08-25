<?php

namespace Core;

use PDO;

class Database 
{
    private static ?PDO $instance = null;

    public static function connect(): PDO 
    {
        if (self::$instance === null) {
            $host = env('DB_HOST', 'localhost');
            $dbname = env('DB_NAME');
            $user = env('DB_USER', 'root');
            $pass = env('DB_PASS', '');
            
            self::$instance = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$instance;
    }
}
