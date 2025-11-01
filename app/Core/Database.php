<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private $host;
    private $dbname;
    private $username;
    private $password;

    private function __construct()
    {
        // Prefer environment variables (e.g., from docker-compose), with sensible fallbacks
        $this->host = getenv('MYSQL_HOST') ?: 'mysql-iskole.alwaysdata.net';
        $this->dbname = getenv('MYSQL_DB') ?: 'iskole_db';
        $this->username = getenv('MYSQL_USER') ?: 'iskole_admin';
        $this->password = getenv('MYSQL_PASSWORD') ?: 'iskole+123';

        $charset = 'utf8mb4';
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
