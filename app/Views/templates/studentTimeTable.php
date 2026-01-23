<?php

$studentInfo = $studentInfo ?? [
    'name' => '—',
    'class' => '—',
    'stu_id' => '—',
];

$currentDay = date('l'); // Get current day name
$currentTime = date('H:i');

$days = $days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

if (!isset($timetable) || !is_array($timetable) || !isset($timetable['periods']) || !isset($timetable['schedule'])) {
    $timetable = ['periods' => [], 'schedule' => array_fill_keys($days, [])];
}

// Statistics
$stats = $stats ?? [
    'total_periods' => 40,
    'subjects_count' => 0,
    'teachers_count' => 0
];
?>

<link rel="stylesheet" href="/css/studentTimetable/studentTimetable.css">

<div class="student-timetable-section">
    <div class="timetable-container">
        <!-- Header -->
        <div class="timetable-header">
            <div class="header-content">
                <div>
                    <h1 class="header-title">My Weekly Timetable</h1>
                    <p class="header-subtitle">View your complete class schedule for the week</p>
                </div>
                <div class="student-info-badge">
                    <div class="info-item">
                        <span class="info-label">Student</span>
                        <span class="info-value"><?php echo htmlspecialchars($studentInfo['name'] ?? '—'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Class</span>
                        <span class="info-value"><?php echo htmlspecialchars($studentInfo['class'] ?? '—'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID</span>
                        <span class="info-value"><?php echo htmlspecialchars($studentInfo['stu_id'] ?? '—'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $stats['total_periods']; ?></div>
                    <div class="stat-label">Total Periods/Week</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $stats['subjects_count']; ?></div>
                    <div class="stat-label">Subjects</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $stats['teachers_count']; ?></div>
                    <div class="stat-label">Teachers</div>
                </div>
            </div>

            <div class="stat-card current-day-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $currentDay; ?></div>
                    <div class="stat-label">Today</div>
                </div>
            </div>
        </div>

        <!-- Timetable -->
        <div class="timetable-wrapper">
            <div class="timetable-scroll">
                <table class="timetable-table">
                    <thead>
                        <tr>
                            <th class="time-column sticky-column">Time</th>
                            <?php foreach ($days as $day): ?>
                                <th class="day-column <?php echo $day === $currentDay ? 'today-column' : ''; ?>">
                                    <div class="day-header">
                                        <span class="day-name"><?php echo $day; ?></span>
                                        <?php if ($day === $currentDay): ?>
                                            <span class="today-badge">Today</span>
                                        <?php endif; ?>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($timetable['periods'])): ?>
                            <tr>
                                <td class="time-cell sticky-column">—</td>
                                <td colspan="5" class="interval-cell">
                                    <div class="interval-content">
                                        <i class="fas fa-info-circle"></i>
                                        <span>No timetable available yet.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach (($timetable['periods'] ?? []) as $index => $period): ?>
                            <tr class="<?php echo $period['period'] === 'INTERVAL' ? 'interval-row' : ''; ?>">
                                <td class="time-cell sticky-column">
                                    <?php if ($period['period'] === 'INTERVAL'): ?>
                                        <div class="interval-time">
                                            <i class="fas fa-coffee"></i>
                                            <span><?php echo $period['time']; ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="time-slot">
                                            <span class="period-number">Period <?php echo $period['period']; ?></span>
                                            <span class="time-range"><?php echo $period['time']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <?php if ($period['period'] === 'INTERVAL'): ?>
                                    <td colspan="5" class="interval-cell">
                                        <div class="interval-content">
                                            <i class="fas fa-mug-hot"></i>
                                            <span>INTERVAL</span>
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <?php foreach ($days as $day): ?>
                                        <?php
                                        $class = $timetable['schedule'][$day][$index] ?? null;
                                        $isToday = $day === $currentDay;
                                        ?>
                                        <td class="class-cell <?php echo $isToday ? 'today-cell' : ''; ?>">
                                            <?php if ($class): ?>
                                                <div class="class-card">
                                                    <div class="subject-name"><?php echo htmlspecialchars($class['subject'] ?? ''); ?></div>
                                                    <div class="class-details">
                                                        <span class="teacher-name">
                                                            <i class="fas fa-user-tie"></i>
                                                            <?php echo htmlspecialchars($class['teacher'] ?? ''); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="timetable-legend">
            <h3 class="legend-title">Legend</h3>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color today-indicator"></div>
                    <span>Today's Classes</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color interval-indicator"></div>
                    <span>Break Time</span>
                </div>
                <div class="legend-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Teacher Name</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight current period based on rendered timetable rows (no hardcoded dummy times)
        const timeSlots = <?php echo json_encode($timetable['periods'] ?? []); ?>;
        const now = new Date();
        const timeInMinutes = now.getHours() * 60 + now.getMinutes();

        function toMinutes(hhmm) {
            const m = String(hhmm || '').trim().match(/^(\d{1,2}):(\d{2})/);
            if (!m) return null;
            return parseInt(m[1], 10) * 60 + parseInt(m[2], 10);
        }

        const ranges = (timeSlots || []).map((p, index) => {
            const t = String(p.time || '');
            if (!t.includes('-')) return { start: null, end: null, index };
            const parts = t.split('-').map(s => s.trim());
            let start = toMinutes(parts[0]);
            let end = toMinutes(parts[1]);
            if (start === null || end === null) return { start: null, end: null, index };
            // Handle 12:50 - 01:30 style (treat as afternoon if end <= start)
            if (end <= start) end += 12 * 60;
            return { start, end, index };
        }).filter(r => r.start !== null && r.end !== null);

        const current = ranges.find(r => timeInMinutes >= r.start && timeInMinutes <= r.end);
        if (current) {
            const rows = document.querySelectorAll('.timetable-table tbody tr');
            if (rows[current.index]) {
                rows[current.index].classList.add('current-period');
            }
        }

        // Add smooth scroll behavior
        const timetableScroll = document.querySelector('.timetable-scroll');
        if (timetableScroll) {
            // Scroll to today's column on mobile
            const todayColumn = document.querySelector('.today-column');
            if (todayColumn && window.innerWidth < 768) {
                const scrollPosition = todayColumn.offsetLeft - 100;
                timetableScroll.scrollTo({
                    left: scrollPosition,
                    behavior: 'smooth'
                });
            }
        }
    });
</script>