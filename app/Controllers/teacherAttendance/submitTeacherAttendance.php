<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Model/teacherAttendance.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

$teacherAttendanceModel = new TeacherAttendance();
header('Content-Type: application/json');
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$date = $data['date'] ?? null;
$attendance = $data['attendance'] ?? [];

if (!$date || empty($attendance)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    return;
}

foreach ($attendance as $teacherId => $status) {
    $ok = $teacherAttendanceModel->updateAttendance($teacherId, $date, $status);

    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Failed to record attendance for teacher ID: ' . $teacherId]);
        return;
    }
}
echo json_encode(['success' => true, 'message' => 'Attendance submitted successfully']);
