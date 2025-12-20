<?php
// Management Panel - Teacher Attendance - Fetches real data from database
require_once __DIR__ . '/../../Model/teacherAttendance.php';

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$today = date('Y-m-d');
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Initialize model and fetch data
$teacherAttendanceModel = new TeacherAttendance();
$teachers = [];
$presentCount = 0;
$absentCount = 0;
$leaveCount = 0;
$totalTeachers = 0;
$attendancePercentage = 0;

try {
    // Fetch teachers with attendance status from database
    $teachers = $teacherAttendanceModel->getTeachersWithAttendance($selectedDate);

    // Filter by search query if provided
    if (!empty($searchQuery)) {
        $teachers = array_filter($teachers, function ($t) use ($searchQuery) {
            return stripos($t['name'], $searchQuery) !== false;
        });
        $teachers = array_values($teachers); // Re-index array
    }

    // Normalize status values (convert 'not-marked' to 'absent' for display)
    foreach ($teachers as &$t) {
        if ($t['status'] === 'not-marked') {
            $t['status'] = 'absent';
        }
        if ($t['status'] === 'late') {
            $t['status'] = 'present';
        }
    }
    unset($t);

    // Calculate statistics
    $presentCount = count(array_filter($teachers, fn($t) => $t['status'] === 'present'));
    $absentCount = count(array_filter($teachers, fn($t) => $t['status'] === 'absent'));
    $leaveCount = count(array_filter($teachers, fn($t) => $t['status'] === 'leave'));
    $totalTeachers = count($teachers);
    $attendancePercentage = $totalTeachers > 0 ? round(($presentCount / $totalTeachers) * 100) : 0;
} catch (Exception $e) {
    error_log("Error loading teacher attendance: " . $e->getMessage());
    // Show error message in UI
    $loadError = "Failed to load teacher data. Please try again.";
}
?>
<link rel="stylesheet" href="/css/mp/teacherAttencence.css">
<section class="attendance-section theme-light" aria-labelledby="teacher-attendance-title">
    <div class="box">
        <div class="heading-section">
            <div class="header-content">
                <div>
                    <h1 class="heading-text" id="teacher-attendance-title">Teacher Attendance</h1>
                    <p class="sub-heding-text">Monitor and record daily attendance of teachers</p>
                </div>
            </div>
        </div>
        <!-- Filter Form -->
        <div class="filter-container">
            <form action="" method="GET" class="filter-form" id="filterForm">
                <input type="hidden" name="tab" value="Attendance">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-select"
                            value="<?php echo htmlspecialchars($selectedDate); ?>" max="<?php echo $today; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="search" class="form-label">Teacher Name</label>
                        <input type="text" name="search" id="search" class="form-select"
                            placeholder="Search name..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                    </div>
                    <div class="form-group btn-group">
                        <button type="submit" class="btn btn-search">
                            <span class="btn-icon">🔍</span>
                            <span class="btn-text">Load Attendance</span>
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
        <!-- Summary -->
        <div class="selection-summary">
            <div class="summary-badge">
                <span class="summary-icon">📅</span>
                <span class="summary-text">Attendance for
                    <strong><?php echo date('l, F j, Y', strtotime($selectedDate)); ?></strong>
                    <?php if ($selectedDate === $today): ?><span class="today-tag">Today</span><?php endif; ?></span>
            </div>
        </div>
        <!-- Stats -->
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
            <!-- <div class="stat-card leave-stat">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $leaveCount; ?></div>
                    <div class="stat-label">On Leave</div>
                </div>
            </div> -->
            <div class="stat-card total-stat">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $attendancePercentage; ?>%</div>
                    <div class="stat-label">Attendance Rate</div>
                </div>
            </div>
        </div>
        <!-- Quick Actions -->
        <!-- <div class="quick-actions">
            <button type="button" class="quick-btn" onclick="markAllTeachers('present')"><span
                    class="quick-icon">✅</span><span class="quick-text">All Present</span></button>
            <button type="button" class="quick-btn" onclick="markAllTeachers('absent')"><span
                    class="quick-icon">❌</span><span class="quick-text">All Absent</span></button>
            <button type="button" class="quick-btn" onclick="markAllTeachers('leave')"><span
                    class="quick-icon">📝</span><span class="quick-text">All Leave</span></button>
            <button type="button" class="quick-btn" onclick="resetAttendance()"><span class="quick-icon">🔄</span><span
                    class="quick-text">Reset</span></button>
        </div> -->
        <!-- Table -->
        <div class="attendance-table-container">
            <form action="#" method="POST" id="teacherAttendanceForm">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                <div class="table-wrapper">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Current Status</th>
                                <th>Mark Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($teachers)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2rem; color: #666;">
                                        <?php if (!empty($searchQuery)): ?>
                                            No teachers found matching "<?php echo htmlspecialchars($searchQuery); ?>"
                                        <?php else: ?>
                                            No teachers found in the system.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($teachers as $i => $t): ?>
                                    <tr class="teacher-row" data-teacher-id="<?php echo $t['id']; ?>"
                                        data-status="<?php echo htmlspecialchars($t['status']); ?>">
                                        <td><?php echo htmlspecialchars($t['id']); ?></td>
                                        <td class="col-name"><?php echo htmlspecialchars($t['name'] ?? 'Unknown'); ?></td>
                                        <td><?php echo htmlspecialchars($t['subject'] ?? 'N/A'); ?></td>
                                        <td class="col-status"><span id="status-<?php echo $t['id']; ?>"
                                                class="status-badge status-<?php echo htmlspecialchars($t['status']); ?>"><?php echo ucfirst($t['status']); ?></span>
                                        </td>
                                        <td class="col-actions">
                                            <div class="action-buttons-group">
                                                <button type="button"
                                                    class="action-btn btn-present <?php echo $t['status'] === 'present' ? 'active' : ''; ?>"
                                                    onclick="markTeacherStatus(<?php echo $t['id']; ?>,'present')" aria-label="Mark Present">✅</button>
                                                <button type="button"
                                                    class="action-btn btn-absent <?php echo $t['status'] === 'absent' ? 'active' : ''; ?>"
                                                    onclick="markTeacherStatus(<?php echo $t['id']; ?>,'absent')" aria-label="Mark Absent">❌</button>
                                                <button type="button"
                                                    class="action-btn btn-leave <?php echo $t['status'] === 'leave' ? 'active' : ''; ?>"
                                                    onclick="markTeacherStatus(<?php echo $t['id']; ?>,'leave')" aria-label="Mark Leave">📝</button>
                                            </div>
                                            <input type="hidden" id="input-<?php echo $t['id']; ?>"
                                                name="attendance[<?php echo $t['id']; ?>]" value="<?php echo htmlspecialchars($t['status']); ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($teachers)): ?>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="cancelAttendance()"><span
                                class="btn-icon">✗</span><span class="btn-text">Cancel</span></button>
                        <button type="submit" class="btn btn-primary"><span class="btn-icon">✓</span><span
                                class="btn-text">Submit Attendance</span></button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>
<script>
    function markTeacherStatus(id, status) {
        const row = document.querySelector(`tr[data-teacher-id="${id}"]`);
        const badge = document.getElementById(`status-${id}`);
        const input = document.getElementById(`input-${id}`);
        const buttons = row.querySelectorAll('.action-btn');
        input.value = status;
        row.dataset.status = status;
        badge.className = `status-badge status-${status}`;
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        buttons.forEach(b => b.classList.remove('active'));
        const target = row.querySelector(`.btn-${status}`);
        if (target) target.classList.add('active');
        updateTeacherStats();
    }

    function markAllTeachers(status) {
        if (confirm(`Mark all teachers as ${status}?`)) {
            document.querySelectorAll('.teacher-row').forEach(r => {
                markTeacherStatus(r.dataset.teacherId, status);
            });
        }
    }

    function resetAttendance() {
        if (confirm('Reset all changes?')) location.reload();
    }

    function updateTeacherStats() {
        let present = 0,
            absent = 0,
            leave = 0;
        const rows = document.querySelectorAll('.teacher-row');
        rows.forEach(r => {
            const s = r.dataset.status;
            if (s === 'present') present++;
            else if (s === 'absent') absent++;
            else if (s === 'leave') leave++;
        });
        const total = rows.length;
        const pct = total ? Math.round((present / total) * 100) : 0;
        document.querySelector('.present-stat .stat-value').textContent = present;
        document.querySelector('.absent-stat .stat-value').textContent = absent;
        document.querySelector('.leave-stat .stat-value').textContent = leave;
        document.querySelector('.total-stat .stat-value').textContent = pct + '%';
    }

    function cancelAttendance() {
        if (confirm('Cancel attendance? Changes will be lost.')) window.history.back();
    }

    // Submit
    document.getElementById('teacherAttendanceForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Collect attendance data
        const attendanceData = {};
        document.querySelectorAll('.teacher-row').forEach(row => {
            const teacherId = row.dataset.teacherId;
            const status = row.dataset.status;
            attendanceData[teacherId] = status;
        });

        const payload = {
            date: document.querySelector('input[name="date"]').value,
            attendance: attendanceData
        };

        // Send to backend using Fetch API
        fetch('/api/teacherAttendance', {
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
                    alert('Teacher attendance submitted successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to submit attendance'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit attendance. Please try again.');
            });
    });
    // Date validation
    document.getElementById('date')?.addEventListener('change', function() {
        const sel = new Date(this.value);
        const t = new Date();
        t.setHours(0, 0, 0, 0);
        if (sel > t) {
            alert('Future dates not allowed');
            this.value = '<?php echo $today; ?>';
        }
    });

    // Live search filter
    document.getElementById('search')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.teacher-row').forEach(row => {
            const name = row.querySelector('.col-name')?.textContent.toLowerCase() || '';
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>