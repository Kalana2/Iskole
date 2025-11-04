    <?php
    // filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\timetable.php

    // Sample timetable data structure
    $teacherInfo = [
        'name' => 'Mr. Perera',
        'subject' => 'Mathematics',
        'employee_id' => 'T-2024-0156'
    ];

    // Time slots for the week
    $timeSlots = [
        ['time' => '07:30 - 08:30', 'period' => 1],
        ['time' => '08:30 - 09:30', 'period' => 2],
        ['time' => '09:30 - 10:30', 'period' => 3],
        ['time' => '10:30 - 11:30', 'period' => 4],
        ['time' => 'INTERVAL', 'period' => 'break'],
        ['time' => '11:50 - 12:50', 'period' => 5],
        ['time' => '12:50 - 13:30', 'period' => 6],
        ['time' => '13:30 - 14:30', 'period' => 7],
        ['time' => '14:30 - 15:10', 'period' => 8]
    ];

    // Timetable data: [day][period] => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203']
    $timetable = [
        'Monday' => [
            1 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            2 => ['class' => '10-B', 'subject' => 'Mathematics', 'room' => '203'],
            3 => ['class' => '11-A', 'subject' => 'Mathematics', 'room' => '205'],
            4 => ['class' => '9-C', 'subject' => 'Mathematics', 'room' => '102'],
            5 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            6 => null, // Free period
            7 => ['class' => '11-B', 'subject' => 'Mathematics', 'room' => '205'],
            8 => null
        ],
        'Tuesday' => [
            1 => null,
            2 => ['class' => '10-B', 'subject' => 'Mathematics', 'room' => '203'],
            3 => ['class' => '11-A', 'subject' => 'Mathematics', 'room' => '205'],
            4 => ['class' => '11-B', 'subject' => 'Mathematics', 'room' => '205'],
            5 => ['class' => '9-A', 'subject' => 'Mathematics', 'room' => '101'],
            6 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            7 => ['class' => '9-B', 'subject' => 'Mathematics', 'room' => '102'],
            8 => ['class' => '10-C', 'subject' => 'Mathematics', 'room' => '204']
        ],
        'Wednesday' => [
            1 => ['class' => '9-A', 'subject' => 'Mathematics', 'room' => '101'],
            2 => ['class' => '9-B', 'subject' => 'Mathematics', 'room' => '102'],
            3 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            4 => ['class' => '10-B', 'subject' => 'Mathematics', 'room' => '203'],
            5 => ['class' => '11-A', 'subject' => 'Mathematics', 'room' => '205'],
            6 => null,
            7 => null,
            8 => ['class' => '11-B', 'subject' => 'Mathematics', 'room' => '205']
        ],
        'Thursday' => [
            1 => ['class' => '10-C', 'subject' => 'Mathematics', 'room' => '204'],
            2 => ['class' => '11-A', 'subject' => 'Mathematics', 'room' => '205'],
            3 => ['class' => '11-B', 'subject' => 'Mathematics', 'room' => '205'],
            4 => ['class' => '9-A', 'subject' => 'Mathematics', 'room' => '101'],
            5 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            6 => ['class' => '10-B', 'subject' => 'Mathematics', 'room' => '203'],
            7 => null,
            8 => ['class' => '9-C', 'subject' => 'Mathematics', 'room' => '102']
        ],
        'Friday' => [
            1 => ['class' => '9-B', 'subject' => 'Mathematics', 'room' => '102'],
            2 => ['class' => '9-C', 'subject' => 'Mathematics', 'room' => '102'],
            3 => ['class' => '10-A', 'subject' => 'Mathematics', 'room' => '203'],
            4 => null,
            5 => ['class' => '10-B', 'subject' => 'Mathematics', 'room' => '203'],
            6 => ['class' => '11-A', 'subject' => 'Mathematics', 'room' => '205'],
            7 => ['class' => '11-B', 'subject' => 'Mathematics', 'room' => '205'],
            8 => null
        ]
    ];

    // Calculate statistics
    $totalClasses = 0;
    $classesPerDay = [];
    foreach ($timetable as $day => $periods) {
        $count = count(array_filter($periods, function ($p) {
            return $p !== null;
        }));
        $classesPerDay[$day] = $count;
        $totalClasses += $count;
    }

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $currentDay = date('l');
    ?>
    <link rel="stylesheet" href="/css/timetable/timetable.css">

    <!--Nav1 : Teacher Timetable-->
    <section class="timetable-section theme-light" aria-labelledby="timetable-title">
        <div class="box">
            <!-- Header Section -->
            <div class="heading-section">
                <div class="header-content">
                    <div>
                        <h1 class="heading-text" id="timetable-title">Teacher Timetable</h1>
                        <p class="sub-heding-text">View your weekly class schedule and assignments</p>
                    </div>
                    <div class="teacher-info-badge">
                        <div class="info-item">
                            <span class="info-label">Teacher:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['name']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Subject:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['subject']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $totalClasses; ?></div>
                        <div class="stat-label">Classes This Week</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo isset($classesPerDay[$currentDay]) ? $classesPerDay[$currentDay] : 0; ?></div>
                        <div class="stat-label">Classes Today</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo round($totalClasses / 5, 1); ?></div>
                        <div class="stat-label">Avg. Classes/Day</div>
                    </div>
                </div>
            </div>

            <!-- Current Day Indicator -->
            <div class="current-day-indicator">
                <span class="indicator-icon">📍</span>
                <span class="indicator-text">Today is <strong><?php echo $currentDay; ?></strong>, <?php echo date('F j, Y'); ?></span>
            </div>

            <!-- Timetable -->
            <div class="timetable-container">
                <div class="table-wrapper">
                    <table class="timetable-table">
                        <thead>
                            <tr>
                                <th class="time-column">Time / Period</th>
                                <?php foreach ($days as $day): ?>
                                    <th class="day-column <?php echo $day === $currentDay ? 'current-day' : ''; ?>">
                                        <?php echo $day; ?>
                                        <?php if ($day === $currentDay): ?>
                                            <span class="today-badge">Today</span>
                                        <?php endif; ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timeSlots as $slot): ?>
                                <?php if ($slot['period'] === 'break'): ?>
                                    <tr class="interval-row">
                                        <td colspan="6" class="interval-cell">
                                            <div class="interval-content">
                                                <span class="interval-icon">☕</span>
                                                <span class="interval-text">INTERVAL (20 minutes)</span>
                                                <span class="interval-time">10:30 - 10:50</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr class="period-row">
                                        <td class="time-cell">
                                            <div class="time-info">
                                                <span class="period-number">Period <?php echo $slot['period']; ?></span>
                                                <span class="time-range"><?php echo $slot['time']; ?></span>
                                            </div>
                                        </td>
                                        <?php foreach ($days as $day): ?>
                                            <td class="class-cell <?php echo $day === $currentDay ? 'today-cell' : ''; ?>">
                                                <?php
                                                $class = isset($timetable[$day][$slot['period']]) ? $timetable[$day][$slot['period']] : null;
                                                if ($class):
                                                ?>
                                                    <div class="class-card">
                                                        <div class="class-name"><?php echo htmlspecialchars($class['class']); ?></div>
                                                        <div class="class-details">
                                                            <span class="room-info">
                                                                <span class="room-icon">🚪</span>
                                                                Room <?php echo htmlspecialchars($class['room']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="free-period">
                                                        <span class="free-text">Free</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Legend -->
            <div class="legend-container">
                <h3 class="legend-title">Legend</h3>
                <div class="legend-items">
                    <div class="legend-item">
                        <span class="legend-box class-box"></span>
                        <span class="legend-label">Scheduled Class</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-box free-box"></span>
                        <span class="legend-label">Free Period</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-box today-box"></span>
                        <span class="legend-label">Today's Column</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-box interval-box"></span>
                        <span class="legend-label">Interval</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Highlight current time slot
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const currentTime = currentHour * 60 + currentMinute; // Time in minutes since midnight

            // Define time ranges in minutes
            const periods = [{
                    start: 7 * 60 + 30,
                    end: 8 * 60 + 30,
                    period: 1
                },
                {
                    start: 8 * 60 + 30,
                    end: 9 * 60 + 30,
                    period: 2
                },
                {
                    start: 9 * 60 + 30,
                    end: 10 * 60 + 30,
                    period: 3
                },
                {
                    start: 10 * 60 + 30,
                    end: 11 * 60 + 30,
                    period: 4
                },
                {
                    start: 11 * 60 + 50,
                    end: 12 * 60 + 50,
                    period: 5
                },
                {
                    start: 12 * 60 + 50,
                    end: 13 * 60 + 30,
                    period: 6
                },
                {
                    start: 13 * 60 + 30,
                    end: 14 * 60 + 30,
                    period: 7
                },
                {
                    start: 14 * 60 + 30,
                    end: 15 * 60 + 10,
                    period: 8
                }
            ];

            // Find current period
            const currentPeriod = periods.find(p => currentTime >= p.start && currentTime <= p.end);

            if (currentPeriod) {
                const rows = document.querySelectorAll('.period-row');
                rows.forEach(row => {
                    const periodText = row.querySelector('.period-number')?.textContent;
                    if (periodText && periodText.includes(currentPeriod.period)) {
                        row.classList.add('current-period');
                    }
                });
            }
        });
    </script>