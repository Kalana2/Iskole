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
        // Use docker-compose env vars when available, with Docker-friendly defaults
        $this->host = getenv('MYSQL_HOST') ?: 'db';
        $this->port = getenv('MYSQL_PORT') ?: '3306';
        $this->dbname = getenv('MYSQL_DB') ?: 'iskole';
        $this->username = getenv('MYSQL_USER') ?: 'root';
        $this->password = getenv('MYSQL_PASSWORD') ?: 'root';

        $charset = 'utf8mb4';
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false,
        ];

        // Retry loop to wait for DB container readiness
        $attempt = 0;
        $maxAttempts = 10;
        $sleepSeconds = 1;
        while (true) {
            try {
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                break; // success
            } catch (PDOException $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    throw new Exception("Database connection failed: " . $e->getMessage());
                }
                sleep($sleepSeconds);
                // Exponential backoff up to 8 seconds
                if ($sleepSeconds < 8) {
                    $sleepSeconds *= 2;
                }
            }
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
