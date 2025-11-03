<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/../app/init.php';
try {
  $pdo = Database::getInstance();
  $pdo->query('SELECT 1');
  echo "Database connection OK. No schema to apply.";
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Install error: ' . $e->getMessage();
}
