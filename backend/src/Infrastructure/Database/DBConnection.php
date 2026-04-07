<?php
declare(strict_types=1);

namespace src\Infrastructure\Database;
use PDO;
use src\config\DBConfig;

class DBConnection
{
    private PDO $conn;
    private static ?DBConnection $instance = null;

    private function __construct()
    {
        $host = DBConfig::$host;
        $user = DBConfig::$user;
        $password = DBConfig::$password;
        $dbname = DBConfig::$dbname;

        $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function getConnection(): PDO
    {
        if(self::$instance === null)
            self::$instance = new DBConnection();
        
        return self::$instance->conn;
    }
}