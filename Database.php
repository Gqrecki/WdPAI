<?php

class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            $host = 'db';
            $port = '5432';
            $dbname = 'db';
            $user = 'docker';
            $password = 'docker';
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
            try {
                self::$conn = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                die('Database connection error: ' . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
