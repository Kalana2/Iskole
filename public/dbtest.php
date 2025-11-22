<?php
require_once __DIR__ . '/../app/Core/Database.php';

echo "<h2>DB Connection Test</h2>";

try {
    $db = Database::getInstance();
    echo "✅ Database connected successfully!";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
