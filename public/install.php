<?php
error_reporting(E_ALL); ini_set('display_errors','1');
require __DIR__ . '/../app/init.php';
try {
  $pdo = Database::getInstance();
  $sql = file_get_contents(__DIR__ . '/../app/Model/schema.sql');
  $pdo->exec($sql);
  echo "Database initialized.";
} catch (Throwable $e) { http_response_code(500); echo 'Install error: '.$e->getMessage(); }
