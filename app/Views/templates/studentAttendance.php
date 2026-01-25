<?php
/**
 * Student Attendance View Template
 * 
 * This template displays attendance data for the logged-in student.
 * Data is fetched from the database via the StudentAttendance model.
 */

// Include the controller to fetch attendance data
require_once __DIR__ . '/../../Controllers/studentAttendance/getStudentAttendanceController.php';

// Fetch real attendance data from the database
$attendanceData = getStudentAttendanceData();

// Extract data for the template
$studentInfo = $attendanceData['studentInfo'];
$attendanceStats = $attendanceData['attendanceStats'];
$monthlyData = $attendanceData['monthlyData'];

// Check if there was an error fetching data
$hasError = isset($attendanceData['error']);
if ($hasError) {
    error_log("Attendance view error: " . $attendanceData['error']);
}

?>

<link rel="stylesheet" href="/css/studentAttendance/studentAttendance.css">

<div class="student-attendance-section">
    <div class="attendance-container">
        <!-- Header -->
        <div class="attendance-header">
            <div class="header-content">
                <div>
                    <h1 class="header-title">
                        <i class="fas fa-calendar-check"></i>
                        Attendance Record
                    </h1>
                    <p class="header-subtitle"><?php echo $studentInfo['name']; ?> - <?php echo $studentInfo['class']; ?> (<?php echo $studentInfo['reg_no']; ?>)</p>
                </div>
                <div class="header-badge">
                    <div class="badge-item">
                        <span class="badge-label">Academic Year</span>
                        <span class="badge-value"><?php echo $studentInfo['year']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card overall-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo number_format($attendanceStats['attendance_rate'], 2); ?>%</div>
                    <div class="stat-label">Overall Attendance</div>
                    <div class="stat-sublabel"><?php echo $attendanceStats['present_days']; ?> of <?php echo $attendanceStats['total_days']; ?> days</div>
                </div>
            </div>

            <div class="stat-card present-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $attendanceStats['present_days']; ?></div>
                    <div class="stat-label">Present Days</div>
                    <div class="stat-sublabel">Regular attendance</div>
                </div>
            </div>

            <div class="stat-card absent-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $attendanceStats['absent_days']; ?></div>
                    <div class="stat-label">Absent Days</div>
                    <div class="stat-sublabel">With valid reasons</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card main-chart">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        12-Month Attendance Overview
                    </h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <div class="chart-card pie-chart">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Attendance Distribution
                    </h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="distributionChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color present-color"></span>
                        <span class="legend-label">Present (<?php echo $attendanceStats['present_days']; ?>)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color absent-color"></span>
                        <span class="legend-label">Absent (<?php echo $attendanceStats['absent_days']; ?>)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pass data to JavaScript -->
<script>
    const attendanceData = {
        // 'monthly' should be supplied by backend in production. Sample is provided above as $monthlyData.
        monthly: <?php echo json_encode($monthlyData); ?>,
        distribution: {
            present: <?php echo $attendanceStats['present_days']; ?>,
            absent: <?php echo $attendanceStats['absent_days']; ?>
        }
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="/js/studentAttendance/studentAttendance.js"></script>