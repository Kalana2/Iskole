    <?php
    // filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\attendance.php

    // Teacher information
    $teacherInfo = [
        'name' => 'Mr. Perera',
        'subject' => 'Mathematics',
        'teacher_id' => 'T-2024-0156'
    ];

    // Available grades and classes
    $grades = [
        ['value' => '6', 'label' => '06'],
        ['value' => '7', 'label' => '07'],
        ['value' => '8', 'label' => '08'],
        ['value' => '9', 'label' => '09'],
        ['value' => '10', 'label' => '10'],
        ['value' => '11', 'label' => '11']
    ];

    $classes = ['A', 'B', 'C'];

    // Sample selected values (when form is submitted)
    $selectedGrade = '10';
    $selectedClass = 'A';
    $selectedDate = date('Y-m-d'); // Today's date
    $today = date('Y-m-d');

    // Sample student data for Grade 10-A with attendance status
    $students = [
        [
            'id' => 1,
            'reg_number' => '2024001',
            'name' => 'Amal Perera',
            'status' => 'present',
            'attendance_rate' => 95,
            'last_absent' => null
        ],
        [
            'id' => 2,
            'reg_number' => '2024002',
            'name' => 'Nimal Silva',
            'status' => 'present',
            'attendance_rate' => 88
        ],
        [
            'id' => 3,
            'reg_number' => '2024003',
            'name' => 'Kumari Fernando',
            'status' => 'present',
            'attendance_rate' => 98
        ],
        [
            'id' => 4,
            'reg_number' => '2024004',
            'name' => 'Saman Rajapaksa',
            'status' => 'absent',
            'attendance_rate' => 85
        ],
        [
            'id' => 5,
            'reg_number' => '2024005',
            'name' => 'Dilini Wickramasinghe',
            'status' => 'present',
            'attendance_rate' => 92
        ],
        [
            'id' => 6,
            'reg_number' => '2024006',
            'name' => 'Kasun Bandara',
            'status' => 'present',
            'attendance_rate' => 90
        ],
        [
            'id' => 7,
            'reg_number' => '2024007',
            'name' => 'Chamari Jayawardena',
            'status' => 'late',
            'attendance_rate' => 87
        ],
        [
            'id' => 8,
            'reg_number' => '2024008',
            'name' => 'Tharindu Gunasekara',
            'status' => 'present',
            'attendance_rate' => 83
        ],
        [
            'id' => 9,
            'reg_number' => '2024009',
            'name' => 'Nethmi Rathnayake',
            'status' => 'present',
            'attendance_rate' => 100
        ],
        [
            'id' => 10,
            'reg_number' => '2024010',
            'name' => 'Isuru Mendis',
            'status' => 'present',
            'attendance_rate' => 91
        ],
        [
            'id' => 11,
            'reg_number' => '2024011',
            'name' => 'Sanduni Perera',
            'status' => 'absent',
            'attendance_rate' => 78
        ],
        [
            'id' => 12,
            'reg_number' => '2024012',
            'name' => 'Lahiru Fernando',
            'status' => 'present',
            'attendance_rate' => 94
        ]
    ];

    // Calculate statistics
    $totalStudents = count($students);
    $presentCount = count(array_filter($students, function ($s) {
        return $s['status'] === 'present';
    }));
    $absentCount = count(array_filter($students, function ($s) {
        return $s['status'] === 'absent';
    }));
    $lateCount = count(array_filter($students, function ($s) {
        return $s['status'] === 'late';
    }));
    // Normalize any existing 'late' statuses to 'present' since late feature removed
    foreach ($students as &$s) {
        if ($s['status'] === 'late') {
            $s['status'] = 'present';
        }
    }

    // Recalculate statistics after normalization
    $totalStudents = count($students);
    $presentCount = count(array_filter($students, function ($s) {
        return $s['status'] === 'present';
    }));
    $absentCount = count(array_filter($students, function ($s) {
        return $s['status'] === 'absent';
    }));

    $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
    ?>
    <link rel="stylesheet" href="/css/attendance/attendance.css">

    <!--Nav2 : Attendance-->
    <section class="attendance-section theme-light" aria-labelledby="attendance-title">
        <div class="box">
            <!-- Header Section -->
            <div class="heading-section">
                <div class="header-content">
                    <div>
                        <h1 class="heading-text" id="attendance-title">Mark Attendance</h1>
                        <p class="sub-heding-text">Record and manage daily attendance for your classes</p>
                    </div>
                    <div class="teacher-info-badge">
                        <div class="info-item">
                            <span class="info-label">Subject:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['subject']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Teacher:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacherInfo['name']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="filter-container">
                <form action="#" method="POST" class="filter-form" id="filterForm">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label for="grade" class="form-label">Grade</label>
                            <select name="grade" id="grade" class="form-select" required>
                                <option value="">Select Grade</option>
                                <?php foreach ($grades as $grade): ?>
                                    <option value="<?php echo $grade['value']; ?>"
                                        <?php echo $selectedGrade === $grade['value'] ? 'selected' : ''; ?>>
                                        Grade <?php echo $grade['label']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class" class="form-label">Class</label>
                            <select name="class" id="class" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class; ?>"
                                        <?php echo $selectedClass === $class ? 'selected' : ''; ?>>
                                        Class <?php echo $class; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date" class="form-label">Date</label>
                            <input
                                type="date"
                                name="date"
                                id="date"
                                class="form-select"
                                value="<?php echo $selectedDate; ?>"
                                max="<?php echo $today; ?>"
                                required>
                        </div>

                        <div class="form-group btn-group">
                            <button type="submit" class="btn btn-search">
                                <span class="btn-icon">🔍</span>
                                <span class="btn-text">Load Students</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Selection Summary -->
            <?php if ($selectedGrade && $selectedClass): ?>
                <div class="selection-summary">
                    <div class="summary-badge">
                        <span class="summary-icon">📅</span>
                        <span class="summary-text">
                            Attendance for: <strong>Grade <?php echo $selectedGrade; ?>-<?php echo $selectedClass; ?></strong> •
                            <strong><?php echo date('l, F j, Y', strtotime($selectedDate)); ?></strong>
                            <?php if ($selectedDate === $today): ?>
                                <span class="today-tag">Today</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="stats-grid">
                    <div class="stat-card present-stat">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $presentCount; ?></div>
                            <div class="stat-label">Present</div>
                        </div>
                    </div>
                    <div class="stat-card absent-stat">
                        <div class="stat-icon">❌</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $absentCount; ?></div>
                            <div class="stat-label">Absent</div>
                        </div>
                    </div>
                    <!-- Late stat removed (feature deprecated) -->
                    <div class="stat-card total-stat">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $attendancePercentage; ?>%</div>
                            <div class="stat-label">Attendance Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button type="button" class="quick-btn" onclick="markAllPresent()">
                        <span class="quick-icon">✅</span>
                        <span class="quick-text">Mark All Present</span>
                    </button>
                    <button type="button" class="quick-btn" onclick="markAllAbsent()">
                        <span class="quick-icon">❌</span>
                        <span class="quick-text">Mark All Absent</span>
                    </button>
                    <button type="button" class="quick-btn" onclick="resetAttendance()">
                        <span class="quick-icon">🔄</span>
                        <span class="quick-text">Reset</span>
                    </button>
                </div>

                <!-- Attendance Table -->
                <div class="attendance-table-container">
                    <form action="#" method="POST" id="attendanceForm">
                        <input type="hidden" name="grade" value="<?php echo htmlspecialchars($selectedGrade); ?>">
                        <input type="hidden" name="class" value="<?php echo htmlspecialchars($selectedClass); ?>">
                        <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">

                        <div class="table-wrapper">
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th class="col-number">#</th>
                                        <th class="col-reg">Reg Number</th>
                                        <th class="col-name">Student Name</th>
                                        <th class="col-rate">Attendance %</th>
                                        <th class="col-status">Current Status</th>
                                        <th class="col-actions">Mark Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $index => $student): ?>
                                        <tr class="student-row" data-student-id="<?php echo $student['id']; ?>" data-status="<?php echo $student['status']; ?>">
                                            <td class="col-number"><?php echo $index + 1; ?></td>
                                            <td class="col-reg">
                                                <span class="reg-badge"><?php echo htmlspecialchars($student['reg_number']); ?></span>
                                            </td>
                                            <td class="col-name">
                                                <div class="student-info">
                                                    <strong class="student-name"><?php echo htmlspecialchars($student['name']); ?></strong>
                                                </div>
                                            </td>
                                            <td class="col-rate">
                                                <div class="rate-display">
                                                    <div class="rate-bar-container">
                                                        <div class="rate-bar" style="width: <?php echo $student['attendance_rate']; ?>%"></div>
                                                    </div>
                                                    <span class="rate-value"><?php echo $student['attendance_rate']; ?>%</span>
                                                </div>
                                            </td>
                                            <td class="col-status">
                                                <span class="status-badge status-<?php echo $student['status']; ?>" id="status-<?php echo $student['id']; ?>">
                                                    <?php echo ucfirst($student['status']); ?>
                                                </span>
                                            </td>
                                            <td class="col-actions">
                                                <div class="action-buttons-group">
                                                    <input type="hidden" name="attendance[<?php echo $student['id']; ?>]" value="<?php echo $student['status']; ?>" id="input-<?php echo $student['id']; ?>">
                                                    <button type="button" class="action-btn btn-present <?php echo $student['status'] === 'present' ? 'active' : ''; ?>"
                                                        onclick="markStatus(<?php echo $student['id']; ?>, 'present')"
                                                        title="Mark Present">
                                                        ✓
                                                    </button>
                                                    <!-- "Late" option removed -->
                                                    <button type="button" class="action-btn btn-absent <?php echo $student['status'] === 'absent' ? 'active' : ''; ?>"
                                                        onclick="markStatus(<?php echo $student['id']; ?>, 'absent')"
                                                        title="Mark Absent">
                                                        ✗
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="cancelAttendance()">
                                <span class="btn-icon">✗</span>
                                <span class="btn-text">Cancel</span>
                            </button>
                            <!-- Save Draft removed -->
                            <button type="submit" class="btn btn-primary">
                                <span class="btn-icon">✓</span>
                                <span class="btn-text">Submit Attendance</span>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h3 class="empty-title">Select Class and Date</h3>
                    <p class="empty-description">Please select grade, class, and date from the filters above to load student attendance list.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Mark attendance status
        function markStatus(studentId, status) {
            const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
            const statusBadge = document.getElementById(`status-${studentId}`);
            const hiddenInput = document.getElementById(`input-${studentId}`);
            const buttons = row.querySelectorAll('.action-btn');

            // Update hidden input
            hiddenInput.value = status;

            // Update status badge
            statusBadge.className = `status-badge status-${status}`;
            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            // Update active button (guard in case button removed)
            buttons.forEach(btn => btn.classList.remove('active'));
            const targetBtn = row.querySelector(`.btn-${status}`);
            if (targetBtn) targetBtn.classList.add('active');

            // Update row data attribute
            row.dataset.status = status;

            // Update statistics
            updateStatistics();
        }

        // Mark all present
        function markAllPresent() {
            if (confirm('Mark all students as present?')) {
                const students = document.querySelectorAll('.student-row');
                students.forEach(row => {
                    const studentId = row.dataset.studentId;
                    markStatus(studentId, 'present');
                });
            }
        }

        // Mark all absent
        function markAllAbsent() {
            if (confirm('Mark all students as absent? This action should be used carefully.')) {
                const students = document.querySelectorAll('.student-row');
                students.forEach(row => {
                    const studentId = row.dataset.studentId;
                    markStatus(studentId, 'absent');
                });
            }
        }

        // Reset attendance to original state
        function resetAttendance() {
            if (confirm('Reset all attendance changes?')) {
                location.reload();
            }
        }

        // Update statistics (late removed)
        function updateStatistics() {
            const rows = document.querySelectorAll('.student-row');
            let present = 0,
                absent = 0;

            rows.forEach(row => {
                const status = row.dataset.status;
                if (status === 'present') present++;
                else if (status === 'absent') absent++;
            });

            const total = rows.length;
            const percentage = total > 0 ? Math.round((present / total) * 100) : 0;

            document.querySelector('.present-stat .stat-value').textContent = present;
            document.querySelector('.absent-stat .stat-value').textContent = absent;
            document.querySelector('.total-stat .stat-value').textContent = percentage + '%';
        }

        // Cancel attendance
        function cancelAttendance() {
            if (confirm('Cancel attendance marking? All changes will be lost.')) {
                window.history.back();
            }
        }

        // Save draft removed (feature deprecated)

        // Form submission
        document.getElementById('attendanceForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const attendanceData = {};

            for (let [key, value] of formData.entries()) {
                if (key.startsWith('attendance[')) {
                    const studentId = key.match(/\d+/)[0];
                    attendanceData[studentId] = value;
                }
            }

            console.log('Submitting attendance:', attendanceData);

            // TODO: Implement actual form submission via AJAX
            alert('Attendance submitted successfully! (This is a demo)');
        });

        // Date validation - only allow today or past dates
        document.getElementById('date')?.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Cannot mark attendance for future dates!');
                this.value = '<?php echo $today; ?>';
            }
        });
    </script>