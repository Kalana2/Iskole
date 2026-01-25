<?php
// Management Panel - Student Attendance - Fetches real data from database
require_once __DIR__ . '/../../Model/StudentAttendance.php';

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$today = date('Y-m-d');
$selectedGrade = isset($_GET['grade']) ? $_GET['grade'] : '';
$selectedClass = isset($_GET['class']) ? $_GET['class'] : '';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$isToday = ($selectedDate === $today); // Check if viewing today's attendance
$isReadOnly = !$isToday; // Read-only mode for past dates

// Initialize model and fetch data
$studentAttendanceModel = new StudentAttendance();
$students = [];
$presentCount = 0;
$absentCount = 0;
$totalStudents = 0;
$attendancePercentage = 0;
$classID = null;

// Get available grades from database
$grades = [];
try {
    $gradeList = $studentAttendanceModel->getGrades();
    foreach ($gradeList as $g) {
        $grades[] = ['value' => $g, 'label' => str_pad($g, 2, '0', STR_PAD_LEFT)];
    }
} catch (Exception $e) {
    error_log("Error loading grades: " . $e->getMessage());
    $grades = [
        ['value' => '6', 'label' => '06'],
        ['value' => '7', 'label' => '07'],
        ['value' => '8', 'label' => '08'],
        ['value' => '9', 'label' => '09'],
        ['value' => '10', 'label' => '10'],
        ['value' => '11', 'label' => '11']
    ];
}

// Get classes for selected grade
$classes = [];
if (!empty($selectedGrade)) {
    try {
        $classList = $studentAttendanceModel->getClassesByGrade($selectedGrade);
        foreach ($classList as $c) {
            $classes[] = $c['section'];
        }
    } catch (Exception $e) {
        error_log("Error loading classes: " . $e->getMessage());
        $classes = ['A', 'B', 'C'];
    }
} else {
    $classes = ['A', 'B', 'C'];
}

// Teacher information (from session if available)
$teacherInfo = [
    'name' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Teacher',
    'subject' => isset($_SESSION['subject']) ? $_SESSION['subject'] : 'General',
    'teacher_id' => isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : ''
];

try {
    // Fetch students with attendance status from database if grade and class selected
    if (!empty($selectedGrade) && !empty($selectedClass)) {
        $classID = $studentAttendanceModel->getClassID($selectedGrade, $selectedClass);

        if ($classID) {
            $students = $studentAttendanceModel->getStudentsWithAttendance($classID, $selectedDate);

            // Get attendance rates for each student
            foreach ($students as &$student) {
                $student['attendance_rate'] = $studentAttendanceModel->getStudentAttendanceRate($student['id']);
            }
            unset($student);

            // Filter by search query if provided
            if (!empty($searchQuery)) {
                $students = array_filter($students, function ($s) use ($searchQuery) {
                    return stripos($s['name'], $searchQuery) !== false ||
                        stripos($s['reg_number'], $searchQuery) !== false;
                });
                $students = array_values($students); // Re-index array
            }

            // Normalize status values (convert 'not-marked' to 'absent' for display)
            foreach ($students as &$s) {
                if ($s['status'] === 'not-marked') {
                    $s['status'] = 'absent';
                } elseif ($s['status'] === 'Late') {
                    $s['status'] = 'present';
                } else {
                    $s['status'] = strtolower($s['status']);
                }
            }
            unset($s);

            // Calculate statistics
            $presentCount = count(array_filter($students, fn($s) => $s['status'] === 'present'));
            $absentCount = count(array_filter($students, fn($s) => $s['status'] === 'absent'));
            $totalStudents = count($students);
            $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
        }
    }
} catch (Exception $e) {
    error_log("Error loading student attendance: " . $e->getMessage());
    // Show error message in UI
    $loadError = "Failed to load student data. Please try again.";
}
?>
<link rel="stylesheet" href="/css/attendance/attendance.css">

<!--Nav2 : Attendance-->
<section class="attendance-section theme-light" aria-labelledby="attendance-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <div class="header-content">
                <div>
                    <h1 class="heading-text" id="attendance-title">Student Attendance</h1>
                    <p class="sub-heding-text">Record and manage daily attendance for your classes</p>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="filter-container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?url=' . ($_GET['url'] ?? 'admin')); ?>" method="GET" class="filter-form" id="filterForm">
                <input type="hidden" name="url" value="<?php echo htmlspecialchars($_GET['url'] ?? 'admin'); ?>">
                <input type="hidden" name="tab" value="Attendance">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="grade" class="form-label">Grade</label>
                        <select name="grade" id="grade" class="form-select" required>
                            <option value="">Select Grade</option>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?php echo $grade['value']; ?>"
                                    <?php echo $selectedGrade == $grade['value'] ? 'selected' : ''; ?>>
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
                            value="<?php echo htmlspecialchars($selectedDate); ?>"
                            max="<?php echo $today; ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="search" class="form-label">Student Name/ID</label>
                        <input type="text" name="search" id="search" class="form-select"
                            placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
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

        <?php if (isset($loadError)): ?>
            <div class="error-message" style="background: #fee; color: #c00; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <strong>Error:</strong> <?php echo htmlspecialchars($loadError); ?>
            </div>
        <?php endif; ?>

        <!-- Selection Summary -->
        <?php if ($selectedGrade && $selectedClass): ?>
            <div class="selection-summary">
                <div class="summary-badge">
                    <span class="summary-icon">📅</span>
                    <span class="summary-text">
                        Attendance for: <strong>Grade <?php echo $selectedGrade; ?>-<?php echo $selectedClass; ?></strong> •
                        <strong><?php echo date('l, F j, Y', strtotime($selectedDate)); ?></strong>
                        <?php if ($isToday): ?>
                            <span class="today-tag">Today</span>
                        <?php endif; ?>
                        <?php if ($isReadOnly): ?>
                            <span class="readonly-tag" style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; margin-left: 8px;">View Only</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php if ($isReadOnly): ?>
                <div class="readonly-notice" style="background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.25rem;">🔒</span>
                    <span><strong>View Only Mode:</strong> You can only modify attendance for today's date. Past attendance records are locked for viewing only.</span>
                </div>
            <?php endif; ?>

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
            <?php if (!$isReadOnly): ?>
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
            <?php endif; ?>

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
                                    <th class="col-status">Current Status</th>
                                    <th class="col-actions">Mark Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 2rem; color: #666;">
                                            <?php if (!empty($searchQuery)): ?>
                                                No students found matching "<?php echo htmlspecialchars($searchQuery); ?>"
                                            <?php else: ?>
                                                No students found in this class.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $index => $student): ?>
                                        <tr class="student-row" data-student-id="<?php echo $student['id']; ?>" data-status="<?php echo htmlspecialchars($student['status']); ?>">
                                            <td class="col-number"><?php echo $index + 1; ?></td>
                                            <td class="col-reg">
                                                <span class="reg-badge"><?php echo htmlspecialchars($student['reg_number'] ?? 'N/A'); ?></span>
                                            </td>
                                            <td class="col-name">
                                                <div class="student-info">
                                                    <strong class="student-name"><?php echo htmlspecialchars($student['name'] ?? 'Unknown'); ?></strong>
                                                </div>
                                            </td>
                                            <!-- <td class="col-rate">
                                                <div class="rate-display">
                                                    <div class="rate-bar-container">
                                                        <div class="rate-bar" style="width: <?php echo $student['attendance_rate'] ?? 0; ?>%"></div>
                                                    </div>
                                                    <span class="rate-value"><?php echo $student['attendance_rate'] ?? 0; ?>%</span>
                                                </div>
                                            </td> -->
                                            <td class="col-status">
                                                <span class="status-badge status-<?php echo htmlspecialchars($student['status']); ?>" id="status-<?php echo $student['id']; ?>">
                                                    <?php echo ucfirst($student['status']); ?>
                                                </span>
                                            </td>
                                            <td class="col-actions">
                                                <div class="action-buttons-group">
                                                    <input type="hidden" name="attendance[<?php echo $student['id']; ?>]" value="<?php echo htmlspecialchars($student['status']); ?>" id="input-<?php echo $student['id']; ?>">
                                                    <button type="button" class="action-btn btn-present <?php echo $student['status'] === 'present' ? 'active' : ''; ?>"
                                                        onclick="markStatus(<?php echo $student['id']; ?>, 'present')"
                                                        title="Mark Present"
                                                        <?php echo $isReadOnly ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                                                        ✓
                                                    </button>
                                                    <button type="button" class="action-btn btn-absent <?php echo $student['status'] === 'absent' ? 'active' : ''; ?>"
                                                        onclick="markStatus(<?php echo $student['id']; ?>, 'absent')"
                                                        title="Mark Absent"
                                                        <?php echo $isReadOnly ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                                                        ✗
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($students) && !$isReadOnly): ?>
                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="cancelAttendance()">
                                <span class="btn-icon">✗</span>
                                <span class="btn-text">Cancel</span>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <span class="btn-icon">✓</span>
                                <span class="btn-text">Submit Attendance</span>
                            </button>
                        </div>
                    <?php endif; ?>
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

        // Update active button
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

    // Update statistics
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

    // Form submission
    document.getElementById('attendanceForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Collect attendance data
        const attendanceData = {};
        document.querySelectorAll('.student-row').forEach(row => {
            const studentId = row.dataset.studentId;
            const status = row.dataset.status;
            attendanceData[studentId] = status;
        });

        const payload = {
            date: document.querySelector('input[name="date"]').value,
            grade: document.querySelector('input[name="grade"]').value,
            class: document.querySelector('input[name="class"]').value,
            attendance: attendanceData
        };

        // Send to backend using Fetch API
        fetch('/api/studentAttendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Student attendance submitted successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to submit attendance'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit attendance. Please try again.');
            });
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

    // Live search filter
    document.getElementById('search')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const name = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
            const regNumber = row.querySelector('.reg-badge')?.textContent.toLowerCase() || '';
            row.style.display = (name.includes(query) || regNumber.includes(query)) ? '' : 'none';
        });
    });
</script>