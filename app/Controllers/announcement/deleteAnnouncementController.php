<?php include_once __DIR__ . '/../../Model/AnnouncementModel.php';
$model = new AnnouncementModel();
// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'delete') {
    header('Content-Type: application/json');
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    $announcement_id = $data['announcement_id'] ?? null;
    if ($announcement_id === null || $announcement_id === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Announcement ID is required']);
        exit;
    }
    $announcement_id = (int)$announcement_id;
    if ($announcement_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Announcement ID']);
        exit;
    }
    try {
        $deleted = $model->deleteAnnouncement($announcement_id);
        if ($deleted) {
            echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Announcement not found or already deleted']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
    exit;
}
//  else {
//     http_response_code(405);
//     echo json_encode(['error' => 'Method Not Allowed']);
// }
