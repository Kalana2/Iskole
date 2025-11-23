<?php
// Only process if this is a POST request with update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    // Suppress any PHP errors/warnings to prevent HTML output
    error_reporting(0);
    ini_set('display_errors', 0);

    // Clean output buffer to prevent any HTML/PHP errors from corrupting JSON
    if (ob_get_level()) {
        ob_clean();
    }

    // Set content type first
    header('Content-Type: application/json');

    try {
        // Include model after headers are set
        include_once __DIR__ . '/../../Model/AnnouncementModel.php';
        $model = new AnnouncementModel();

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        // Validate JSON parsing
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
            exit;
        }

        // Validate required fields
        if (!$data || !isset($data['announcement_id']) || !isset($data['title']) || !isset($data['body'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit;
        }

        $announcement_id = (int)$data['announcement_id'];

        // Validate announcement ID
        if ($announcement_id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid announcement ID']);
            exit;
        }

        // Prepare data for model (convert body to content)
        $updateData = [
            'title' => trim($data['title']),
            'content' => trim($data['body']),
            'audience' => isset($data['audience']) ? (is_array($data['audience']) ? implode(',', $data['audience']) : $data['audience']) : ''
        ];

        // Validate data
        if (empty($updateData['title']) || empty($updateData['content'])) {
            echo json_encode(['success' => false, 'error' => 'Title and content cannot be empty']);
            exit;
        }

        $result = $model->updateAnnouncement($announcement_id, $updateData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Announcement updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update announcement in database']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    }
    exit;
}
// If not a POST request with update action, do nothing (don't output anything)
