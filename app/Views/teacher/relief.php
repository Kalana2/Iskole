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
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count(array_unique(array_column($reliefPeriods, 'date'))); ?>
                    </div>
                    <div class="stat-label">Days with Relief</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo array_sum(array_column($reliefPeriods, 'student_count')); ?>
                    </div>
                    <div class="stat-label">Total Students</div>
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
                                    <th>Room</th>
                                    <th>Absent Teacher</th>
                                    <th>Students</th>
                                    <th>Action</th>
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
                                        <td data-label="Room">
                                            <span class="room-text">Room <?php echo htmlspecialchars($period['room']); ?></span>
                                        </td>
                                        <td data-label="Absent Teacher">
                                            <?php echo htmlspecialchars($period['absent_teacher']); ?>
                                        </td>
                                        <td data-label="Students">
                                            <span class="student-count"><?php echo $period['student_count']; ?></span>
                                        </td>
                                        <td data-label="Action">
                                            <button class="btn btn-assign"
                                                data-period-id="<?php echo $period['id']; ?>">Assign</button>
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

<!-- Assign Popup Modal -->
<div class="assign-modal-backdrop" id="assignModal" style="display:none;">
    <div class="assign-modal">
        <button type="button" class="assign-close" id="assignClose">&times;</button>
        <h2 class="assign-title">Assign Relief Period</h2>
        <form id="assignForm" class="assign-form">
            <input type="hidden" name="id" />
            <div class="assign-grid">
                <label>Period<input name="period" readonly /></label>
                <label>Time<input name="time" readonly /></label>
                <label>Class<input name="class" readonly /></label>
                <label>Subject<input name="subject" readonly /></label>
            </div>
            <label>Notes<textarea name="notes" rows="3" placeholder="Add notes"></textarea></label>
            <div class="assign-actions">
                <button type="button" class="btn" id="assignCancel">Cancel</button>
                <button type="submit" class="btn btn-assign">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
    const reliefData = <?php echo json_encode($reliefPeriods); ?>;
    function openAssignModal(p) {
        const modal = document.getElementById('assignModal');
        const f = document.getElementById('assignForm');
        f.id.value = p.id; f.period.value = p.period; f.time.value = p.time; f.class.value = p.grade + '-' + p.class; f.subject.value = p.subject; f.notes.value = '';
        modal.style.display = 'flex';
    }
    function closeAssignModal() { document.getElementById('assignModal').style.display = 'none'; }
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-assign').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.getAttribute('data-period-id'), 10);
                const rec = reliefData.find(r => r.id === id); if (rec) openAssignModal(rec);
            });
        });
        document.getElementById('assignClose').addEventListener('click', closeAssignModal);
        document.getElementById('assignCancel').addEventListener('click', closeAssignModal);
        document.getElementById('assignForm').addEventListener('submit', e => { e.preventDefault(); alert('Relief assigned'); closeAssignModal(); });
    });
</script>