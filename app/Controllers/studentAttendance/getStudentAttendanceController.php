<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Model/StudentAttendance.php';

// Main controller function that takes data from the model
function getStudentAttendanceData()
{
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return [
            'error' => 'User not logged in',
            'studentInfo' => getDefaultStudentInfo(),
            'attendanceStats' => getDefaultAttendanceStats(),
            'monthlyData' => getDefaultMonthlyData()
        ];
    }

    $userID = (int) $_SESSION['user_id'];
    $userRole = isset($_SESSION['user_role']) ? (int) $_SESSION['user_role'] : 3;

    // Validate role - only students (3) and parents (4) can access this view
    if ($userRole !== 3 && $userRole !== 4) {
        return [
            'error' => 'Unauthorized access. Only students and parents can view attendance.',
            'studentInfo' => getDefaultStudentInfo(),
            'attendanceStats' => getDefaultAttendanceStats(),
            'monthlyData' => getDefaultMonthlyData()
        ];
    }

    try {
        $attendanceModel = new StudentAttendance();
        $context = $attendanceModel->getStudentAttendanceContext($userID, $userRole);

        return [
            'studentInfo' => [
                'name' => $context['studentInfo']['name'] ?? 'Unknown Student',
                'class' => $context['studentInfo']['class'] ?? 'Unknown Class',
                'reg_no' => $context['studentInfo']['reg_no'] ?? 'N/A',
                'year' => $context['studentInfo']['year'] ?? date('Y')
            ],
            'attendanceStats' => $context['attendanceStats'],
            'monthlyData' => $context['monthlyData']
        ];
    } catch (Exception $e) {
        error_log("Error in getStudentAttendanceData: " . $e->getMessage());

        // Return default data on error
        return [
            'error' => $e->getMessage(),
            'studentInfo' => getDefaultStudentInfo(),
            'attendanceStats' => getDefaultAttendanceStats(),
            'monthlyData' => getDefaultMonthlyData()
        ];
    }
}

/**
 * ======================= Functions to load default data ====================
 */

/**
 * Default student info when data is unavailable
 */
function getDefaultStudentInfo()
{
    return [
        'name' => 'Unknown Student',
        'class' => 'Unknown Class',
        'reg_no' => 'N/A',
        'year' => date('Y')
    ];
}

/**
 * Default attendance stats when data is unavailable
 */
function getDefaultAttendanceStats()
{
    return [
        'total_days' => 0,
        'present_days' => 0,
        'absent_days' => 0,
        'attendance_rate' => 0,
        'this_month_rate' => 0,
        'last_month_rate' => 0
    ];
}

/**
 * Default monthly data when data is unavailable
 */
function getDefaultMonthlyData()
{
    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $monthlyData = [];

    foreach ($monthNames as $month) {
        $monthlyData[] = [
            'month' => $month,
            'present' => 0,
            'absent' => 0,
            'total' => 0
        ];
    }

    return $monthlyData;
}
