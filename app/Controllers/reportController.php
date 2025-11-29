<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Controller.php';

// Error logging setup
ini_set('display_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $reportType = $_POST['report_type'] ?? null;
    $category = $_POST['category'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;

    // Debug: පෙන්වන්න යැයි කුමන data එක ගිහින් ඇතිදැයි
    error_log("POST Data - Type: $reportType, Category: $category, Title: $title, Desc: $description");

    if(!$reportType || !$category || !$title || !$description) {
        error_log("Validation failed - Missing field(s)");
        header("Location: /report?error=missing_fields");
        exit();
    }

    try {
        $pdo = Database::getInstance();
        error_log("Database connected successfully");
        
        $stmt = $pdo->prepare("INSERT INTO report (report_type, category, title, description, report_date) VALUES (?, ?, ?, ?, NOW())");
        
        error_log("Statement prepared successfully");
        
        $result = $stmt->execute([$reportType, $category, $title, $description]);
        
        error_log("Insert executed - Result: " . ($result ? 'Success' : 'Failed'));
        
        header("Location: /report?success=1");
        exit();
    } catch (Exception $e) {
        error_log("Exception caught - Error: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        header("Location: /report?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>