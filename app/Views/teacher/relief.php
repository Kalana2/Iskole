<?php
// filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\relief.php

// Get today's date
$today = date('Y-m-d');

// Sample data for relief periods - only today's relief periods
$reliefPeriods = [
    [
        'id' => 1,
        'date' => $today,
        'period' => 1,
        'time' => '07:30 - 08:30',
        'grade' => '10',
        'class' => 'A',
        'subject' => 'Mathematics',
        'absent_teacher' => 'Mrs. Jayawardena',
        'reason' => 'Medical Leave',
        'room' => '203',
        'student_count' => 35
    ],
    [
        'id' => 2,
        'date' => $today,
        'period' => 4,
        'time' => '10:30 - 11:30',
        'grade' => '11',
        'class' => 'B',
        'subject' => 'English',
        'absent_teacher' => 'Mr. Fernando',
        'reason' => 'Official Duty',
        'room' => '105',
        'student_count' => 32
    ],
    [
        'id' => 3,
        'date' => $today,
        'period' => 5,
        'time' => '11:30 - 12:30',
        'grade' => '9',
        'class' => 'C',
        'subject' => 'Science',
        'absent_teacher' => 'Ms. Silva',
        'reason' => 'Personal Leave',
        'room' => '301',
        'student_count' => 30
    ],
    [
        'id' => 4,
        'date' => $today,
        'period' => 7,
        'time' => '13:30 - 14:30',
        'grade' => '10',
        'class' => 'B',
        'subject' => 'History',
        'absent_teacher' => 'Mr. Bandara',
        'reason' => 'Medical Leave',
        'room' => '208',
        'student_count' => 33
    ]
];

// Group by date for better organization
$groupedPeriods = [];
foreach ($reliefPeriods as $period) {
    $date = $period['date'];
    if (!isset($groupedPeriods[$date])) {
        $groupedPeriods[$date] = [];
    }
    $groupedPeriods[$date][] = $period;
}
?>
<link rel="stylesheet" href="/css/relief/relief.css">
<link rel="stylesheet" href="/css/relief/teacherAssign.css">

<!--Nav8 : relief-periods-->
<section class="relief-periods-section theme-light" aria-labelledby="relief-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <h1 class="heading-text" id="relief-title">Today's Relief Periods</h1>
            <p class="sub-heding-text">View and manage your relief teaching periods for today -
                <?php echo date('l, F j, Y'); ?></p>
        </div>

        <!-- Summary Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count($reliefPeriods); ?></div>
                    <div class="stat-label">Total Relief Periods</div>
                </div>
            </div>
        </div>

        <!-- Relief Periods by Date -->
        <?php if (!empty($groupedPeriods)): ?>
            <?php foreach ($groupedPeriods as $date => $periods): ?>
                <div class="date-group">
                    <div class="date-header">
                        <h2 class="date-title">
                            <?php
                            $dateTs = strtotime($date);
                            $today = date('Y-m-d');
                            $tomorrow = date('Y-m-d', strtotime('+1 day'));

                            if ($date === $today) {
                                echo 'Today - ';
                            } elseif ($date === $tomorrow) {
                                echo 'Tomorrow - ';
                            }
                            echo date('l, F j, Y', $dateTs);
                            ?>
                        </h2>
                        <span class="period-count"><?php echo count($periods); ?>
                            period<?php echo count($periods) > 1 ? 's' : ''; ?></span>
                    </div>

                    <div class="relief-table-container">
                        <table class="relief-table">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Time</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Absent Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($periods as $period): ?>
                                    <tr class="relief-row">
                                        <td data-label="Period">
                                            <span class="period-badge">Period <?php echo $period['period']; ?></span>
                                        </td>
                                        <td data-label="Time">
                                            <span class="time-text"><?php echo htmlspecialchars($period['time']); ?></span>
                                        </td>
                                        <td data-label="Class">
                                            <span class="class-badge">
                                                <?php echo htmlspecialchars($period['grade'] . '-' . $period['class']); ?>
                                            </span>
                                        </td>
                                        <td data-label="Subject">
                                            <strong><?php echo htmlspecialchars($period['subject']); ?></strong>
                                        </td>
                                        <td data-label="Absent Teacher">
                                            <?php echo htmlspecialchars($period['absent_teacher']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3 class="empty-title">No Relief Periods Assigned</h3>
                <p class="empty-description">You don't have any relief periods scheduled at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Note: Assign modal and assignment actions removed as requested -->