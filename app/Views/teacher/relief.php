<?php
// Controller provides: $selectedDate, $reliefPeriods, $error
$selectedDate = $selectedDate ?? date('Y-m-d');
$reliefPeriods = $reliefPeriods ?? [];
$error = $error ?? null;

// Period → time mapping (mirrors teacher timetable page)
$periodTimes = [
    1 => '07:30 - 08:30',
    2 => '08:30 - 09:30',
    3 => '09:30 - 10:30',
    4 => '10:30 - 11:30',
    5 => '11:50 - 12:50',
    6 => '12:50 - 13:30',
    7 => '13:30 - 14:30',
    8 => '14:30 - 15:10',
];

$totalStudents = 0;
foreach ($reliefPeriods as $p) {
    $totalStudents += (int)($p['studentCount'] ?? 0);
}
?>
<link rel="stylesheet" href="/css/relief/relief.css">
<link rel="stylesheet" href="/css/relief/teacherAssign.css">

<!--Nav8 : relief-periods-->
<section class="relief-periods-section theme-light" aria-labelledby="relief-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <div class="header-content">
                <div>
                    <h1 class="heading-text" id="relief-title">Relief Periods</h1>
                    <p class="sub-heding-text">Your relief teaching periods for <b><?php echo htmlspecialchars($selectedDate); ?></b></p>
                </div>

                <div class="relief-info-badge">
                    <div class="info-item">
                        <span class="info-label">Total Relief Periods</span>
                        <span class="info-value"><?php echo count($reliefPeriods); ?></span>
                    </div>        
                </div>
            </div>
        </div>

        <!--<form method="get" class="relief-form" style="margin-bottom: 12px;">
            <div class="form-actions" style="justify-content: flex-start; gap: 12px;">
                <label style="display:flex; align-items:center; gap:8px;">
                    <span style="min-width: 90px;">Date</span>
                    <input class="input" type="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>" />
                </label>
                <button type="submit" class="btn btn-ghost">Load</button>
            </div>
        </form>-->

        <?php if ($error): ?>
            <div class="assign-log" aria-live="polite"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Summary Stats -->
        <!--<div class="stats-grid">
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
                    <div class="stat-value"><?php echo htmlspecialchars($selectedDate); ?></div>
                    <div class="stat-label">Selected Date</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo (int)$totalStudents; ?></div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>-->

        <!-- Relief Periods Table -->
        <?php if (!empty($reliefPeriods)): ?>
            <div class="relief-table-container">
                <table class="relief-table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Time</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Absent Teacher</th>
                            <th>Students</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reliefPeriods as $period): ?>
                            <?php
                            $periodNo = (int)($period['periodID'] ?? 0);
                            $time = $periodTimes[$periodNo] ?? 'N/A';
                            $grade = (string)($period['grade'] ?? '');
                            $section = (string)($period['section'] ?? '');
                            $classLabel = trim($grade) !== '' ? ($grade . '-' . $section) : (string)($period['classID'] ?? '');
                            $subjectName = (string)($period['subjectName'] ?? '');
                            $absentTeacherName = (string)($period['absentTeacherName'] ?? '');
                            $studentCount = (int)($period['studentCount'] ?? 0);
                            $classId = (int)($period['classID'] ?? 0);
                            ?>
                            <tr class="relief-row">
                                <td data-label="Period">
                                    <span class="period-badge">Period <?php echo $periodNo; ?></span>
                                </td>
                                <td data-label="Time">
                                    <span class="time-text"><?php echo htmlspecialchars($time); ?></span>
                                </td>
                                <td data-label="Class">
                                    <span class="class-badge"><?php echo htmlspecialchars($classLabel); ?></span>
                                </td>
                                <td data-label="Subject">
                                    <strong><?php echo htmlspecialchars($subjectName); ?></strong>
                                </td>
                                <td data-label="Absent Teacher">
                                    <?php echo htmlspecialchars($absentTeacherName); ?>
                                </td>
                                <td data-label="Students">
                                    <span class="student-count"><?php echo $studentCount; ?></span>
                                </td>
                                <td data-label="Action">
                                    <button
                                        type="button"
                                        class="btn btn-view"
                                        data-class-id="<?php echo $classId; ?>"
                                        data-class-label="<?php echo htmlspecialchars($classLabel); ?>"
                                        data-period="<?php echo $periodNo; ?>"
                                        data-time="<?php echo htmlspecialchars($time); ?>"
                                        data-subject="<?php echo htmlspecialchars($subjectName); ?>"
                                        data-absent-teacher="<?php echo htmlspecialchars($absentTeacherName); ?>"
                                    >View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3 class="empty-title">No Relief Periods Assigned</h3>
                <p class="empty-description">You don't have any relief periods scheduled at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Students Modal -->
<div class="assign-modal-backdrop" id="studentsModal" style="display:none;">
    <div class="assign-modal">
        <button type="button" class="assign-close" id="studentsClose">&times;</button>
        <h2 class="assign-title">Relief Details</h2>
        <div class="assign-form">
            <div class="assign-grid">
                <label>Period<input id="mPeriod" readonly /></label>
                <label>Time<input id="mTime" readonly /></label>
                <label>Class<input id="mClass" readonly /></label>
                <label>Subject<input id="mSubject" readonly /></label>
            </div>
            <label>Absent Teacher<input id="mAbsentTeacher" readonly /></label>
            <label>Students</label>
            <div id="studentsList" style="max-height: 280px; overflow:auto; border: 1px solid rgba(0,0,0,.08); border-radius: 10px; padding: 10px; background: #fff;"></div>
            <div class="assign-actions" style="margin-top: 12px;">
                <button type="button" class="btn" id="studentsOk">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const selectedDate = <?php echo json_encode($selectedDate); ?>;

    function openStudentsModal(meta) {
        document.getElementById('mPeriod').value = 'Period ' + meta.period;
        document.getElementById('mTime').value = meta.time;
        document.getElementById('mClass').value = meta.classLabel;
        document.getElementById('mSubject').value = meta.subject;
        document.getElementById('mAbsentTeacher').value = meta.absentTeacher;
        document.getElementById('studentsList').textContent = 'Loading...';
        document.getElementById('studentsModal').style.display = 'flex';

        fetch(`/relief/students?classID=${encodeURIComponent(meta.classId)}&date=${encodeURIComponent(selectedDate)}`)
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(resp => {
                if (!resp || !resp.success) {
                    document.getElementById('studentsList').textContent = 'Failed to load students.';
                    return;
                }
                const students = resp.students || [];
                if (!students.length) {
                    document.getElementById('studentsList').textContent = 'No students found for this class.';
                    return;
                }
                const ul = document.createElement('ul');
                ul.style.margin = '0';
                ul.style.paddingLeft = '18px';
                students.forEach(s => {
                    const li = document.createElement('li');
                    li.textContent = s.name || ('Student ' + (s.id || ''));
                    ul.appendChild(li);
                });
                const container = document.getElementById('studentsList');
                container.innerHTML = '';
                container.appendChild(ul);
            })
            .catch(() => {
                document.getElementById('studentsList').textContent = 'Failed to load students.';
            });
    }

    function closeStudentsModal() {
        document.getElementById('studentsModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', () => {
                const meta = {
                    classId: btn.getAttribute('data-class-id'),
                    classLabel: btn.getAttribute('data-class-label'),
                    period: btn.getAttribute('data-period'),
                    time: btn.getAttribute('data-time'),
                    subject: btn.getAttribute('data-subject'),
                    absentTeacher: btn.getAttribute('data-absent-teacher')
                };
                openStudentsModal(meta);
            });
        });
        document.getElementById('studentsClose').addEventListener('click', closeStudentsModal);
        document.getElementById('studentsOk').addEventListener('click', closeStudentsModal);
    });
</script>