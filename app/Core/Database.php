<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    private function __construct()
    {
        // Load database credentials from .env file
        $this->host = getenv('MYSQL_HOST') ?: 'localhost';
        $this->port = getenv('MYSQL_PORT') ?: '3306';
        $this->dbname = getenv('MYSQL_DB') ?: 'default_db';
        $this->username = getenv('MYSQL_USER') ?: 'root';
        $this->password = getenv('MYSQL_PASSWORD') ?: '';

        $charset = 'utf8mb4';
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false,
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
