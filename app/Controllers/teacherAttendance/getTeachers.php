<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Model/teacherAttendance.php';

header('Content-Type: application/json');

try {
    $teacherAttendanceModel = new TeacherAttendance();

    // Get date from request (default to today)
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        throw new Exception('Invalid date format');
    }

    // Get teachers with their attendance status
    $teachers = $teacherAttendanceModel->getTeachersWithAttendance($date);

    // Get attendance statistics
    $stats = $teacherAttendanceModel->getAttendanceStats($date);

    echo json_encode([
        'success' => true,
        'date' => $date,
        'teachers' => $teachers,
        'stats' => [
            'total' => (int)$stats['total'],
            'present' => (int)$stats['present'],
            'absent' => (int)$stats['absent'],
            'leave' => (int)$stats['on_leave'],
            'percentage' => $stats['total'] > 0
                ? round(($stats['present'] / $stats['total']) * 100)
                : 0
        ]
    ]);
} catch (Exception $e) {
    error_log("Error in getTeachers: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch teachers: ' . $e->getMessage()
    ]);
}
