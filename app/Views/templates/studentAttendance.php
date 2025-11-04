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
    'late_days' => 5,
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

// Recent attendance records
$recentAttendance = [
    ['date' => '2025-11-04', 'day' => 'Monday', 'status' => 'present', 'time_in' => '07:45 AM', 'remarks' => ''],
    ['date' => '2025-11-01', 'day' => 'Friday', 'status' => 'present', 'time_in' => '07:50 AM', 'remarks' => ''],
    ['date' => '2025-10-31', 'day' => 'Thursday', 'status' => 'present', 'time_in' => '07:42 AM', 'remarks' => ''],
    ['date' => '2025-10-30', 'day' => 'Wednesday', 'status' => 'late', 'time_in' => '08:15 AM', 'remarks' => 'Heavy traffic'],
    ['date' => '2025-10-29', 'day' => 'Tuesday', 'status' => 'present', 'time_in' => '07:48 AM', 'remarks' => ''],
    ['date' => '2025-10-28', 'day' => 'Monday', 'status' => 'present', 'time_in' => '07:40 AM', 'remarks' => ''],
    ['date' => '2025-10-25', 'day' => 'Friday', 'status' => 'absent', 'time_in' => '-', 'remarks' => 'Medical leave'],
    ['date' => '2025-10-24', 'day' => 'Thursday', 'status' => 'present', 'time_in' => '07:55 AM', 'remarks' => ''],
    ['date' => '2025-10-23', 'day' => 'Wednesday', 'status' => 'present', 'time_in' => '07:47 AM', 'remarks' => ''],
    ['date' => '2025-10-22', 'day' => 'Tuesday', 'status' => 'present', 'time_in' => '07:52 AM', 'remarks' => '']
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

            <div class="stat-card late-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $attendanceStats['late_days']; ?></div>
                    <div class="stat-label">Late Arrivals</div>
                    <div class="stat-sublabel">After 8:00 AM</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card main-chart">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        4-Week Attendance Summary
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
                    <div class="legend-item">
                        <span class="legend-color late-color"></span>
                        <span class="legend-label">Late (<?php echo $attendanceStats['late_days']; ?>)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="recent-attendance-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i>
                    Recent Attendance Records
                </h3>
            </div>
            <div class="attendance-table-wrapper">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Time In</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAttendance as $record): ?>
                            <tr>
                                <td>
                                    <span class="date-display"><?php echo date('M d, Y', strtotime($record['date'])); ?></span>
                                </td>
                                <td>
                                    <span class="day-display"><?php echo $record['day']; ?></span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $record['status']; ?>">
                                        <?php if ($record['status'] == 'present'): ?>
                                            <i class="fas fa-check-circle"></i> Present
                                        <?php elseif ($record['status'] == 'absent'): ?>
                                            <i class="fas fa-times-circle"></i> Absent
                                        <?php else: ?>
                                            <i class="fas fa-clock"></i> Late
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="time-display"><?php echo $record['time_in']; ?></span>
                                </td>
                                <td>
                                    <span class="remarks-text"><?php echo $record['remarks'] ?: '-'; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pass data to JavaScript -->
<script>
    const attendanceData = {
        weekly: <?php echo json_encode($weeklyData); ?>,
        distribution: {
            present: <?php echo $attendanceStats['present_days']; ?>,
            absent: <?php echo $attendanceStats['absent_days']; ?>,
            late: <?php echo $attendanceStats['late_days']; ?>
        }
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="/js/studentAttendance/studentAttendance.js"></script>