<?php
// Timetable data is provided by StudentController when the "Time Table" tab is active.
// Fall back to sample data if nothing is provided.
$studentInfo = $studentInfo ?? [
    'name' => '—',
    'class' => '—',
    'reg_no' => '—',
];

$currentDay = date('l'); // Get current day name
$currentTime = date('H:i');

// Timetable structure
$timetable = $timetable ?? [
    'periods' => [
        ['time' => '07:50 - 08:30', 'period' => 1],
        ['time' => '08:30 - 09:10', 'period' => 2],
        ['time' => '09:10 - 09:50', 'period' => 3],
        ['time' => '09:50 - 10:30', 'period' => 4],
        ['time' => '10:30 - 10:50', 'period' => 'INTERVAL'],
        ['time' => '10:50 - 11:30', 'period' => 5],
        ['time' => '11:30 - 12:10', 'period' => 6],
        ['time' => '12:10 - 12:50', 'period' => 7],
        ['time' => '12:50 - 01:30', 'period' => 8]
    ],
    'schedule' => [
        'Monday' => [
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'Science', 'teacher' => 'Mrs. Perera'],
            ['subject' => 'English', 'teacher' => 'Ms. Fernando'],
            ['subject' => 'Citizenship Education', 'teacher' => 'Mr. Rathnayake'],
            null, // Interval
            ['subject' => 'Geography', 'teacher' => 'Mrs. Wijesinghe'],
            ['subject' => 'History', 'teacher' => 'Mr. Gunawardena'],
            ['subject' => 'Tamil', 'teacher' => 'Mrs. Rajendran'],
            ['subject' => 'Physical Education', 'teacher' => 'Mr. Jayawardena']
        ],
        'Tuesday' => [
            ['subject' => 'Science', 'teacher' => 'Mrs. Perera'],
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'Geography', 'teacher' => 'Mrs. Wijesinghe'],
            ['subject' => 'English', 'teacher' => 'Ms. Fernando'],
            null, // Interval
            ['subject' => 'History', 'teacher' => 'Mr. Gunawardena'],
            ['subject' => 'Sinhala', 'teacher' => 'Mrs. Kumari'],
            ['subject' => 'ICT', 'teacher' => 'Mr. De Silva'],
            ['subject' => 'Aesthetics', 'teacher' => 'Ms. Rodrigo']
        ],
        'Wednesday' => [
            ['subject' => 'English', 'teacher' => 'Ms. Fernando'],
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'Science', 'teacher' => 'Mrs. Perera'],
            null, // Interval
            ['subject' => 'Geography', 'teacher' => 'Mrs. Wijesinghe'],
            ['subject' => 'Sinhala', 'teacher' => 'Mrs. Kumari'],
            ['subject' => 'Religion', 'teacher' => 'Mr. Samaraweera'],
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva']
        ],
        'Thursday' => [
            ['subject' => 'History', 'teacher' => 'Mr. Gunawardena'],
            ['subject' => 'Tamil', 'teacher' => 'Mrs. Rajendran'],
            ['subject' => 'P.T.S.', 'teacher' => 'Mrs. Bandara'],
            ['subject' => 'Geography', 'teacher' => 'Mrs. Wijesinghe'],
            null, // Interval
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'English', 'teacher' => 'Ms. Fernando'],
            ['subject' => 'Science', 'teacher' => 'Mrs. Perera'],
            ['subject' => 'ICT', 'teacher' => 'Mr. De Silva']
        ],
        'Friday' => [
            ['subject' => 'Health', 'teacher' => 'Mrs. Jayasuriya'],
            ['subject' => 'History', 'teacher' => 'Mr. Gunawardena'],
            ['subject' => 'English', 'teacher' => 'Ms. Fernando'],
            ['subject' => 'Science', 'teacher' => 'Mrs. Perera'],
            null, // Interval
            ['subject' => 'Mathematics', 'teacher' => 'Mr. Silva'],
            ['subject' => 'Geography', 'teacher' => 'Mrs. Wijesinghe'],
            ['subject' => 'Sinhala', 'teacher' => 'Mrs. Kumari'],
            ['subject' => 'Club Activities', 'teacher' => 'Various']
        ]
    ]
];

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
                        <span class="info-label">Reg No</span>
                        <span class="info-value"><?php echo htmlspecialchars($studentInfo['reg_no'] ?? '—'); ?></span>
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
                            <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day): ?>
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
                        <?php foreach ($timetable['periods'] as $index => $period): ?>
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
                                    <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day): ?>
                                        <?php
                                        $class = $timetable['schedule'][$day][$index];
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
        // Highlight current period
        const currentTime = new Date();
        const hours = currentTime.getHours();
        const minutes = currentTime.getMinutes();
        const timeInMinutes = hours * 60 + minutes;

        const periods = [{
                start: 7 * 60 + 50,
                end: 8 * 60 + 30,
                index: 0
            },
            {
                start: 8 * 60 + 30,
                end: 9 * 60 + 10,
                index: 1
            },
            {
                start: 9 * 60 + 10,
                end: 9 * 60 + 50,
                index: 2
            },
            {
                start: 9 * 60 + 50,
                end: 10 * 60 + 30,
                index: 3
            },
            {
                start: 10 * 60 + 30,
                end: 10 * 60 + 50,
                index: 4
            }, // Interval
            {
                start: 10 * 60 + 50,
                end: 11 * 60 + 30,
                index: 5
            },
            {
                start: 11 * 60 + 30,
                end: 12 * 60 + 10,
                index: 6
            },
            {
                start: 12 * 60 + 10,
                end: 12 * 60 + 50,
                index: 7
            },
            {
                start: 12 * 60 + 50,
                end: 13 * 60 + 30,
                index: 8
            }
        ];

        periods.forEach(period => {
            if (timeInMinutes >= period.start && timeInMinutes <= period.end) {
                const rows = document.querySelectorAll('.timetable-table tbody tr');
                if (rows[period.index]) {
                    rows[period.index].classList.add('current-period');
                }
            }
        });

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