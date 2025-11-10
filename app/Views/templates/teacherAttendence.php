<?php
// Management Panel - Teacher Attendance (sample data + UI similar to student attendance)
// Mock teacher data (replace with DB query results)
$selectedDate = date('Y-m-d');
$today = date('Y-m-d');
$subjects = ['Mathematics', 'Science', 'English', 'History', 'Geography', 'IT'];
$teachers = [
    ['id' => 101, 'emp_no' => 'T-2024-0001', 'name' => 'Sunil Perera', 'subject' => 'Mathematics', 'status' => 'present', 'attendance_rate' => 97],
    ['id' => 102, 'emp_no' => 'T-2024-0002', 'name' => 'Nimali Silva', 'subject' => 'Science', 'status' => 'absent', 'attendance_rate' => 92],
    ['id' => 103, 'emp_no' => 'T-2024-0003', 'name' => 'Kasun Fernando', 'subject' => 'English', 'status' => 'present', 'attendance_rate' => 95],
    ['id' => 104, 'emp_no' => 'T-2024-0004', 'name' => 'Dilusha Jayawardena', 'subject' => 'History', 'status' => 'present', 'attendance_rate' => 99],
    ['id' => 105, 'emp_no' => 'T-2024-0005', 'name' => 'Tharindu Rathnayake', 'subject' => 'Geography', 'status' => 'present', 'attendance_rate' => 93],
    ['id' => 106, 'emp_no' => 'T-2024-0006', 'name' => 'Isuri Mendis', 'subject' => 'IT', 'status' => 'absent', 'attendance_rate' => 90],
];
// Normalize deprecated status if any (e.g., 'late' -> 'present')
foreach ($teachers as &$t) {
    if ($t['status'] === 'late') {
        $t['status'] = 'present';
    }
}
$presentCount = count(array_filter($teachers, fn($t) => $t['status'] === 'present'));
$absentCount = count(array_filter($teachers, fn($t) => $t['status'] === 'absent'));
$totalTeachers = count($teachers);
$attendancePercentage = $totalTeachers > 0 ? round(($presentCount / $totalTeachers) * 100) : 0;
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
            <form action="#" method="POST" class="filter-form" id="filterForm">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-select"
                            value="<?php echo $selectedDate; ?>" max="<?php echo $today; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <select name="subject" id="subject" class="form-select">
                            <option value="">All Subjects</option>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search" class="form-label">Teacher Name</label>
                        <input type="text" name="search" id="search" class="form-select" placeholder="Search name...">
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
            <!-- 'On Leave' feature removed -->
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
            <button type="button" class="quick-btn" onclick="markAllTeachers('present')"><span
                    class="quick-icon">✅</span><span class="quick-text">All Present</span></button>
            <button type="button" class="quick-btn" onclick="markAllTeachers('absent')"><span
                    class="quick-icon">❌</span><span class="quick-text">All Absent</span></button>
            <!-- 'All Leave' quick action removed -->
            <button type="button" class="quick-btn" onclick="resetAttendance()"><span class="quick-icon">🔄</span><span
                    class="quick-text">Reset</span></button>
        </div>
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
                            <?php foreach ($teachers as $i => $t): ?>
                                <tr class="teacher-row" data-teacher-id="<?php echo $t['id']; ?>"
                                    data-status="<?php echo $t['status']; ?>">
                                    <td><?php echo $i + 1; ?></td>
                                    <td class="col-name"><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td><?php echo htmlspecialchars($t['subject']); ?></td>
                                    <td class="col-status"><span id="status-<?php echo $t['id']; ?>"
                                            class="status-badge status-<?php echo $t['status']; ?>"><?php echo ucfirst($t['status']); ?></span>
                                    </td>
                                    <td class="col-actions">
                                        <div class="action-buttons-group">
                                            <button type="button"
                                                class="action-btn btn-present <?php echo $t['status'] === 'present' ? 'active' : ''; ?>"
                                                onclick="markTeacherStatus(<?php echo $t['id']; ?>,'present')" aria-label="Mark Present">✅</button>
                                            <button type="button"
                                                class="action-btn btn-absent <?php echo $t['status'] === 'absent' ? 'active' : ''; ?>"
                                                onclick="markTeacherStatus(<?php echo $t['id']; ?>,'absent')" aria-label="Mark Absent">❌</button>
                                            <!-- Leave option removed -->
                                        </div>
                                        <input type="hidden" id="input-<?php echo $t['id']; ?>"
                                            name="attendance[<?php echo $t['id']; ?>]" value="<?php echo $t['status']; ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="cancelAttendance()"><span
                            class="btn-icon">✗</span><span class="btn-text">Cancel</span></button>
                    <button type="submit" class="btn btn-primary"><span class="btn-icon">✓</span><span
                            class="btn-text">Submit Attendance</span></button>
                </div>
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
            absent = 0;
        const rows = document.querySelectorAll('.teacher-row');
        rows.forEach(r => {
            const s = r.dataset.status;
            if (s === 'present') present++;
            else if (s === 'absent') absent++;
        });
        const total = rows.length;
        const pct = total ? Math.round((present / total) * 100) : 0;
        document.querySelector('.present-stat .stat-value').textContent = present;
        document.querySelector('.absent-stat .stat-value').textContent = absent;
        document.querySelector('.total-stat .stat-value').textContent = pct + '%';
    }

    function cancelAttendance() {
        if (confirm('Cancel attendance? Changes will be lost.')) window.history.back();
    }
    // Submit
    document.getElementById('teacherAttendanceForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        const data = {};
        for (const [k, v] of fd.entries()) {
            if (k.startsWith('attendance[')) {
                const id = k.match(/\d+/)[0];
                data[id] = v;
            }
        }
        console.log('Submitting teacher attendance', data);
        alert('Teacher attendance submitted (demo).');
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
</script>