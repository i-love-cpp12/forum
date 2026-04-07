<?php
declare(strict_types=1);

namespace src\Infrastructure\Database;
use PDO;
use config\DBConfig;

class DBConnection
{
    public static function getConnection(): PDO
    {
        $host = DBConfig::$host;
        $user = DBConfig::$user;
        $password = DBConfig::$password;
        $dbname = DBConfig::$dbname;

        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    }
}