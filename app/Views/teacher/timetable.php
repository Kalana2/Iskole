    <?php
    // filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\timetable.php

    // Data is provided by TeacherController when the "Time Table" tab is active.
    // Fall back to sample structures if not provided.
    $teacherInfo = $teacherInfo ?? [
        'name' => '—',
        'subject' => '—',
        'employee_id' => '—'
    ];

    $timeSlots = $timeSlots ?? [
        ['time' => '07:50 - 08:30', 'period' => 1, 'startTime' => '07:50', 'endTime' => '08:30'],
        ['time' => '08:30 - 09:10', 'period' => 2, 'startTime' => '08:30', 'endTime' => '09:10'],
        ['time' => '09:10 - 09:50', 'period' => 3, 'startTime' => '09:10', 'endTime' => '09:50'],
        ['time' => '09:50 - 10:30', 'period' => 4, 'startTime' => '09:50', 'endTime' => '10:30'],
        ['time' => '10:30 - 10:50', 'period' => 'break', 'startTime' => '10:30', 'endTime' => '10:50'],
        ['time' => '10:50 - 11:30', 'period' => 5, 'startTime' => '10:50', 'endTime' => '11:30'],
        ['time' => '11:30 - 12:10', 'period' => 6, 'startTime' => '11:30', 'endTime' => '12:10'],
        ['time' => '12:10 - 12:50', 'period' => 7, 'startTime' => '12:10', 'endTime' => '12:50'],
        ['time' => '12:50 - 13:30', 'period' => 8, 'startTime' => '12:50', 'endTime' => '13:30']
    ];

    $days = $days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $timetable = $timetable ?? array_fill_keys($days, [1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null, 8 => null]);

    $totalClasses = $totalClasses ?? 0;
    $classesPerDay = $classesPerDay ?? array_fill_keys($days, 0);
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
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['name'] ?? '—'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Subject:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['subject'] ?? '—'); ?></span>
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
                                                <span class="interval-text">INTERVAL</span>
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
                                                            <span class="subject-info">
                                                                <span class="subject-icon">📘</span>
                                                                <?php echo htmlspecialchars($class['subject'] ?? ''); ?>
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

            const timeSlots = <?php echo json_encode($timeSlots); ?>;

            function toMinutes(hhmm) {
                if (!hhmm) return null;
                const m = String(hhmm).match(/^(\d{2}):(\d{2})/);
                if (!m) return null;
                return parseInt(m[1], 10) * 60 + parseInt(m[2], 10);
            }

            // Build time ranges from server-provided period times
            const periods = (timeSlots || [])
                .filter(s => s && s.period !== 'break')
                .map(s => {
                    const start = toMinutes(s.startTime) ?? (String(s.time || '').includes('-') ? toMinutes(String(s.time).split('-')[0].trim()) : null);
                    const end = toMinutes(s.endTime) ?? (String(s.time || '').includes('-') ? toMinutes(String(s.time).split('-')[1].trim()) : null);
                    return { start, end, period: s.period };
                })
                .filter(p => typeof p.period === 'number' && p.start !== null && p.end !== null);

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