<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Model/StudentAttendance.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

$studentAttendanceModel = new StudentAttendance();
header('Content-Type: application/json');
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$date = $data['date'] ?? null;
$grade = $data['grade'] ?? null;
$classSection = $data['class'] ?? null;
$attendance = $data['attendance'] ?? [];

if (!$date || !$grade || !$classSection || empty($attendance)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data: date, grade, class, and attendance are required']);
    return;
}

// Get classID from grade and section
$classID = $studentAttendanceModel->getClassID($grade, $classSection);
if (!$classID) {
    echo json_encode(['success' => false, 'message' => 'Class not found for the specified grade and section']);
    return;
}

// Get markedBy (teacher ID) from session if available
$markedBy = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;

foreach ($attendance as $studentId => $status) {
    // Normalize status to match database ENUM values
    $normalizedStatus = ucfirst(strtolower($status));
    if ($normalizedStatus === 'Not-marked') {
        $normalizedStatus = 'Absent';
    }

    $ok = $studentAttendanceModel->updateAttendance($studentId, $classID, $date, $normalizedStatus, $markedBy);

    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Failed to record attendance for student ID: ' . $studentId]);
        return;
    }
}
echo json_encode(['success' => true, 'message' => 'Attendance submitted successfully']);
