<?php
require_once __DIR__ . '/../../Model/materialModel.php';

header('Content-Type: application/json');

try {
    // Ensure session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Session not found. Please login again.']);
        exit;
    }

    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        exit;
    }

    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($data['materialID']) || empty($data['materialID'])) {
        echo json_encode(['success' => false, 'message' => 'Material ID is required.']);
        exit;
    }

    $materialID = $data['materialID'];

    // Validate materialID is numeric
    if (!is_numeric($materialID)) {
        echo json_encode(['success' => false, 'message' => 'Invalid material ID.']);
        exit;
    }

    // Create model instance and hide material
    $model = new Material();
    $result = $model->deleteMaterial($materialID);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Material hidden successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to hide material. Material not found or access denied.']);
    }
} catch (Exception $e) {
    error_log('Hide material error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
