<?php
// Sample attendance data
$studentInfo = [
    'name' => 'Kasun Perera',
    'class' => 'Grade 10-A',
    'reg_no' => 'STU2024-1025',
    'year' => '2025'
];

// Attendance statistics
$attendanceStats = [
    'total_days' => 180,
    'present_days' => 165,
    'absent_days' => 10,
    'attendance_rate' => 91.67,
    'this_month_rate' => 95.45,
    'last_month_rate' => 88.24
];

// 4-week attendance summary (last 4 weeks)
$weeklyData = [
    ['week' => 'Week 1 (Oct 7-11)', 'present' => 5, 'absent' => 0, 'late' => 0, 'total' => 5],
    ['week' => 'Week 2 (Oct 14-18)', 'present' => 4, 'absent' => 1, 'late' => 0, 'total' => 5],
    ['week' => 'Week 3 (Oct 21-25)', 'present' => 3, 'absent' => 1, 'late' => 1, 'total' => 5],
    ['week' => 'Week 4 (Oct 28-Nov 1)', 'present' => 4, 'absent' => 0, 'late' => 1, 'total' => 5]
];

// 12-month attendance summary (backend should supply this in production)
$monthlyData = [
    ['month' => 'Jan', 'present' => 20, 'absent' => 2, 'total' => 22],
    ['month' => 'Feb', 'present' => 18, 'absent' => 2, 'total' => 20],
    ['month' => 'Mar', 'present' => 21, 'absent' => 1, 'total' => 22],
    ['month' => 'Apr', 'present' => 19, 'absent' => 3, 'total' => 22],
    ['month' => 'May', 'present' => 20, 'absent' => 2, 'total' => 22],
    ['month' => 'Jun', 'present' => 17, 'absent' => 5, 'total' => 22],
    ['month' => 'Jul', 'present' => 22, 'absent' => 0, 'total' => 22],
    ['month' => 'Aug', 'present' => 21, 'absent' => 1, 'total' => 22],
    ['month' => 'Sep', 'present' => 20, 'absent' => 2, 'total' => 22],
    ['month' => 'Oct', 'present' => 20, 'absent' => 2, 'total' => 22],
    ['month' => 'Nov', 'present' => 15, 'absent' => 0, 'total' => 15],
    ['month' => 'Dec', 'present' => 0, 'absent' => 0, 'total' => 0]
];

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