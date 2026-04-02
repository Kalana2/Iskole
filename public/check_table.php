<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/../app/init.php';

try {
    $pdo = Database::getInstance();

    // Check if table exists and get structure
    $stmt = $pdo->query("DESCRIBE examTimeTable");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>examTimeTable structure:</h2>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

    // Try to get sample data
    $stmt = $pdo->query("SELECT * FROM examTimeTable LIMIT 1");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Sample data:</h2>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";

} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}
