<?php
// Simple DB connection check. Remove this file after testing.
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../app/init.php';

try {
    $pdo = Database::getInstance();
    $ok = $pdo->query('SELECT 1')->fetchColumn();
    echo ($ok == 1) ? "DB OK" : "DB FAIL";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'DB ERROR: ' . $e->getMessage();
}
