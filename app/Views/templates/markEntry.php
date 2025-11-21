<?php
    
    $teacherInfo = $teacherInfo ?? ['name' => '', 'subject' => ''];
    $grades = $grades ?? [];
    $classes = $classes ?? [];
    $terms = $terms ?? [];
    //$examTypes = $examTypes ?? [];
    $selectedGrade = $selectedGrade ?? '';
    $selectedClass = $selectedClass ?? '';
    $selectedTerm = $selectedTerm ?? '';
    $selectedExamType = $selectedExamType ?? '';
    $students = $students ?? [];
    $message = $message ?? null;

    // Calculate statistics from supplied students if controller didn't
    $totalStudents = $totalStudents ?? count($students);
    $marksEntered = $marksEntered ?? count(array_filter($students, function ($s) {
        return isset($s['previous_marks']) && $s['previous_marks'] !== null && $s['previous_marks'] !== '';
    }));
    $marksPending = $marksPending ?? ($totalStudents - $marksEntered);
    $completionPercentage = $completionPercentage ?? ($totalStudents > 0 ? round(($marksEntered / $totalStudents) * 100) : 0);
    $classAverage = $classAverage ?? 0;
    if (empty($classAverage)) {
        $enteredMarks = array_filter(array_column($students, 'previous_marks'), function ($m) {
            return $m !== null && $m !== '';
        });
        $classAverage = !empty($enteredMarks) ? round(array_sum($enteredMarks) / count($enteredMarks), 2) : 0;
    }
    ?>
    <link rel="stylesheet" href="/css/markEntry/markEntry.css">

    <!--Nav1 : Marks Entry-->
    <section class="marks-entry-section theme-light" aria-labelledby="marks-entry-title">
        <div class="box">
            <!-- Header Section -->
            <div class="heading-section">
                <div class="header-content">
                    <div>
                        <h1 class="heading-text" id="marks-entry-title">Enter Student Marks</h1>
                        <p class="sub-heding-text">Record and manage examination marks for your students</p>
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

            <?php if (!empty($message)): ?>
                <div class="flash-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

                <!-- Filter Form -->
                <div class="filter-container">
                    <form method="POST" class="filter-form" id="filterForm">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label for="grade" class="form-label">Grade</label>
                            <select name="grade" id="grade" class="form-select" required>
                                <option value="">Select Grade</option>
                                <?php if (empty($grades)): ?>
                                    <option value="" disabled>No grades available</option>
                                <?php else: ?>
                                    <?php foreach ($grades as $grade): ?>
                                        <option value="<?php echo $grade['value']; ?>" <?php echo $selectedGrade === $grade['value'] ? 'selected' : ''; ?>>
                                            Grade <?php echo $grade['label']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class" class="form-label">Class</label>
                            <select name="class" id="class" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php if (empty($classes)): ?>
                                    <option value="" disabled>No classes available</option>
                                <?php else: ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo $class; ?>" <?php echo $selectedClass === $class ? 'selected' : ''; ?>>
                                            Class <?php echo $class; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="term" class="form-label">Term</label>
                            <select name="term" id="term" class="form-select" required>
                                <option value="">Select Term</option>
                                <?php if (empty($terms)): ?>
                                    <option value="" disabled>No terms available</option>
                                <?php else: ?>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?php echo $term['value']; ?>" <?php echo $selectedTerm === $term['value'] ? 'selected' : ''; ?>>
                                            <?php echo $term['label']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group btn-group">
                            <button type="button" class="btn btn-search" id="loadStudentsBtn">
                                <span class="btn-icon">🔍</span>
                                <span class="btn-text">Load Students</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Selection Summary -->
            <?php if ($selectedGrade && $selectedClass): ?>
                <?php
                // Safely resolve term and exam type labels
                $termLabel = '-';
                if (!empty($terms) && $selectedTerm !== '') {
                    foreach ($terms as $t) {
                        if (isset($t['value']) && (string)$t['value'] === (string)$selectedTerm) {
                            $termLabel = $t['label'];
                            break;
                        }
                    }
                }
                /*$examTypeLabel = '-';
                if (!empty($examTypes) && $selectedExamType !== '') {
                    foreach ($examTypes as $et) {
                        if (isset($et['value']) && (string)$et['value'] === (string)$selectedExamType) {
                            $examTypeLabel = $et['label'];
                            break;
                        }
                    }
                }*/
                ?>
                <div class="selection-summary">
                    <div class="summary-badge">
                        <span class="summary-icon">📋</span>
                        <span class="summary-text">
                            Showing: <strong>Grade <?php echo $selectedGrade; ?>-<?php echo $selectedClass; ?></strong> •
                            <strong><?php echo htmlspecialchars($termLabel); ?></strong>
                            <!--<strong><?php echo htmlspecialchars($examTypeLabel); ?></strong>-->
                        </span>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $totalStudents; ?></div>
                            <div class="stat-label">Total Students</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $marksEntered; ?></div>
                            <div class="stat-label">Marks Entered</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $marksPending; ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $classAverage; ?>%</div>
                            <div class="stat-label">Class Average</div>
                        </div>
                    </div>
                </div>

                <!-- Marks Entry Table -->
                <div class="marks-table-container">
                    <form action="/MarkEntry" method="POST" id="marksForm">
                        <input type="hidden" name="grade" value="<?php echo htmlspecialchars($selectedGrade); ?>">
                        <input type="hidden" name="class" value="<?php echo htmlspecialchars($selectedClass); ?>">
                        <input type="hidden" name="term" value="<?php echo htmlspecialchars($selectedTerm); ?>">
                        <input type="hidden" name="examType" value="<?php echo htmlspecialchars($selectedExamType); ?>">

                        <div class="table-wrapper">
                            <table class="marks-table">
                                <thead>
                                    <tr>
                                        <th class="col-number">#</th>
                                        <th class="col-reg">Reg Number</th>
                                        <th class="col-name">Student Name</th>
                                        <th class="col-prev">Previous</th>
                                        <th class="col-marks">Current Marks</th>
                                        <th class="col-grade">Grade</th>
                                        <th class="col-attendance">Attendance</th>
                                        <th class="col-status">Status</th>
                                        <th class="col-action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $index => $student): ?>
                                        <?php
                                        $grade = '';
                                        $gradeClass = '';
                                        if ($student['current_marks'] !== null) {
                                            if ($student['current_marks'] >= 75) {
                                                $grade = 'A';
                                                $gradeClass = 'grade-a';
                                            } elseif ($student['current_marks'] >= 65) {
                                                $grade = 'B';
                                                $gradeClass = 'grade-b';
                                            } elseif ($student['current_marks'] >= 55) {
                                                $grade = 'C';
                                                $gradeClass = 'grade-c';
                                            } elseif ($student['current_marks'] >= 35) {
                                                $grade = 'S';
                                                $gradeClass = 'grade-s';
                                            } else {
                                                $grade = 'W';
                                                $gradeClass = 'grade-w';
                                            }
                                        }
                                        ?>
                                        <tr class="student-row <?php echo $student['current_marks'] === null ? 'pending' : 'completed'; ?>">
                                            <td class="col-number"><?php echo $index + 1; ?></td>
                                            <td class="col-reg">
                                                <span class="reg-badge"><?php echo htmlspecialchars($student['reg_number']); ?></span>
                                            </td>
                                            <td class="col-name">
                                                <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                            </td>
                                            <td class="col-prev">
                                                <span class="prev-marks"><?php echo $student['previous_marks']; ?></span>
                                            </td>
                                            <td class="col-marks">
                                                <div class="marks-input-wrapper">
                                                    <input
                                                        type="number"
                                                        name="marks[<?php echo $student['id']; ?>]"
                                                        class="marks-input"
                                                        min="0"
                                                        max="100"
                                                        value="<?php echo $student['current_marks']; ?>"
                                                        placeholder="0-100"
                                                        data-student-id="<?php echo $student['id']; ?>"
                                                        onchange="calculateGrade(this)">
                                                    <span class="marks-total">/ 100</span>
                                                </div>
                                            </td>
                                            <td class="col-grade">
                                                <span class="grade-badge <?php echo $gradeClass; ?>" id="grade-<?php echo $student['id']; ?>">
                                                    <?php echo $grade ?: '-'; ?>
                                                </span>
                                            </td>
                                            <td class="col-attendance">
                                                <span class="attendance-badge"><?php echo $student['attendance']; ?>%</span>
                                            </td>
                                            <td class="col-status">
                                                <span class="status-badge <?php echo $student['current_marks'] !== null ? 'status-entered' : 'status-pending'; ?>">
                                                    <?php echo $student['current_marks'] !== null ? 'Entered' : 'Pending'; ?>
                                                </span>
                                            </td>
                                            <td class="col-action">
                                                <?php if ($student['previous_marks'] !== null): ?>
                                                    <button type="button" class="status-badge status-remove" onclick="removeMarks(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name']); ?>')" title="Remove previous marks">
                                                        Remove
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" onclick="clearAllMarks()">
                                <span class="btn-icon">🗑️</span>
                                <span class="btn-text">Clear All</span>
                            </button>
                            <!--<button type="button" class="btn btn-secondary" onclick="saveDraft()">
                                <span class="btn-icon">💾</span>
                                <span class="btn-text">Save Draft</span>
                            </button>-->
                            <button type="submit" class="btn btn-primary">
                                <span class="btn-icon">✓</span>
                                <span class="btn-text">Submit Marks</span>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <h3 class="empty-title">Select Class and Exam</h3>
                    <p class="empty-description">Please select grade, class and term from the filters above to load student list.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Calculate grade based on marks
        function calculateGrade(input) {
            const marks = parseInt(input.value);
            const studentId = input.dataset.studentId;
            const gradeBadge = document.getElementById('grade-' + studentId);

            if (isNaN(marks) || marks < 0 || marks > 100) {
                gradeBadge.textContent = '-';
                gradeBadge.className = 'grade-badge';
                return;
            }

            let grade = '';
            let gradeClass = '';

            if (marks >= 75) {
                grade = 'A';
                gradeClass = 'grade-a';
            } else if (marks >= 65) {
                grade = 'B';
                gradeClass = 'grade-b';
            } else if (marks >= 55) {
                grade = 'C';
                gradeClass = 'grade-c';
            } else if (marks >= 35) {
                grade = 'S';
                gradeClass = 'grade-s';
            } else {
                grade = 'W';
                gradeClass = 'grade-w';
            }

            gradeBadge.textContent = grade;
            gradeBadge.className = 'grade-badge ' + gradeClass;
        }

        // Remove marks from database
        function removeMarks(studentId, studentName) {
            if (!confirm('Remove previous marks for ' + studentName + '? This action cannot be undone.')) {
                return;
            }
            
            const grade = document.querySelector('input[name="grade"]').value;
            const classVal = document.querySelector('input[name="class"]').value;
            const term = document.querySelector('input[name="term"]').value;
            
            if (!grade || !classVal || !term) {
                alert('Grade, Class, and Term are required');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'deleteMarks');
            formData.append('studentId', studentId);
            formData.append('grade', grade);
            formData.append('class', classVal);
            formData.append('term', term);
            
            fetch('/index.php?url=markEntry/deleteMarks', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) { alert('Error: ' + (data.error || 'Unknown error')); return; }
                    // Remove the mark from the previous marks column and hide button
                    const row = document.querySelector('button[onclick*="removeMarks('+studentId+'"]').closest('tr');
                    if (row) {
                        const prevMarksCell = row.querySelector('.prev-marks');
                        if (prevMarksCell) prevMarksCell.textContent = '-';
                        const removeBtn = row.querySelector('.btn-remove');
                        if (removeBtn) removeBtn.style.display = 'none';
                    }
                    alert('Marks removed successfully');
                })
                .catch(err => { console.error('Delete error:', err); alert('Delete failed: ' + err.message); });
        }

        // Clear all marks
        function clearAllMarks() {
            if (confirm('Are you sure you want to clear all entered marks?')) {
                const inputs = document.querySelectorAll('.marks-input');
                inputs.forEach(input => {
                    input.value = '';
                    calculateGrade(input);
                });
            }
        }

        // Save as draft
        function saveDraft() {
            console.log('Saving draft...');
            // TODO: Implement AJAX save functionality
            alert('Draft saved successfully! (This is a demo)');
        }

        // Form validation
        var _marksForm = document.getElementById('marksForm');
        if (_marksForm) {
            _marksForm.addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.marks-input');
            let allFilled = true;

            inputs.forEach(input => {
                if (!input.value || input.value === '') {
                    allFilled = false;
                }
            });

            if (!allFilled) {
                if (!confirm('Some marks are still pending. Do you want to submit anyway?')) {
                    e.preventDefault();
                    return false;
                }
            }

            // Allow normal form submission to the server
        });
        }

        // Auto-save functionality (every 2 minutes)
        setInterval(function() {
            console.log('Auto-saving draft...');
            // TODO: Implement auto-save via AJAX
        }, 120000);

        // Re-enabled AJAX loading to prevent page refresh
        var _loadBtn = document.getElementById('loadStudentsBtn');
        if (_loadBtn) {
            _loadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const grade = document.getElementById('grade').value;
                const classVal = document.getElementById('class').value;
                const term = document.getElementById('term').value;
                if (!grade || !classVal || !term) {
                    alert('Please select Grade, Class and Term');
                    return;
                }
                const formData = new FormData();
                formData.append('grade', grade);
                formData.append('class', classVal);
                formData.append('term', term);
                fetch('/MarkEntry/loadStudents', { method: 'POST', body: formData })
                    .then(r => {
                        const ct = r.headers.get('Content-Type') || '';
                        if (!ct.includes('application/json')) return r.text().then(t => { throw new Error('Unexpected response: ' + t.substring(0,120)); });
                        return r.json();
                    })
                    .then(data => {
                        console.log('Raw API response:', data);
                        if (!data.success) { alert('Error: ' + (data.error || 'Unknown error')); return; }
                        updateStudentTable(data);
                    })
                    .catch(err => { console.error('Fetch error:', err); alert('Load failed: ' + err.message); });
            });
        }

        function updateStudentTable(data) {
            try {
                console.log('updateStudentTable received:', data);
                // Ensure stats grid and selection summary exist (server rendered only when initially selected)
                let summary = document.querySelector('.selection-summary');
                if (!summary) {
                    const box = document.querySelector('.box');
                    if (box) {
                        summary = document.createElement('div');
                        summary.className = 'selection-summary';
                        summary.innerHTML = '<div class="summary-badge"><span class="summary-icon">📋</span><span class="summary-text"></span></div>';
                        const statsGrid = box.querySelector('.stats-grid');
                        if (statsGrid) box.insertBefore(summary, statsGrid); else box.appendChild(summary);
                    }
                }
                if (summary) {
                    const termOption = document.querySelector('#term option[value="'+(data.selectedTerm||'')+'"]');
                    const termLabel = termOption ? termOption.textContent : ('Term '+ (data.selectedTerm || '-'));
                    summary.querySelector('.summary-text').innerHTML = 'Showing: <strong>Grade '+escapeHtml(data.selectedGrade)+'-'+escapeHtml(data.selectedClass)+'</strong> • <strong>'+escapeHtml(termLabel)+'</strong>';
                }
                // Stats
                /*if (data.stats) {
                    const statValues = document.querySelectorAll('.stat-card .stat-value');
                    if (statValues.length >= 4) {
                        statValues[0].textContent = data.stats.totalStudents;
                        statValues[1].textContent = data.stats.marksEntered;
                        statValues[2].textContent = data.stats.marksPending;
                        statValues[3].textContent = (data.stats.classAverage || 0) + '%';
                    }
                }*/

                // Stats Section
if (data.stats) {
    let statsGrid = document.querySelector('.stats-grid');

    // If stats grid is missing (AJAX initial load), create it
    if (!statsGrid) {
        const box = document.querySelector('.box');
        statsGrid = document.createElement('div');
        statsGrid.className = 'stats-grid';

        statsGrid.innerHTML = `
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value" id="totalStudents">0</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <div class="stat-value" id="marksEntered">0</div>
                    <div class="stat-label">Marks Entered</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-content">
                    <div class="stat-value" id="marksPending">0</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <div class="stat-value" id="classAverage">0%</div>
                    <div class="stat-label">Class Average</div>
                </div>
            </div>
        `;

        // Insert stats before the table container
        const tableContainer = document.querySelector('.marks-table-container');
        if (tableContainer) {
            box.insertBefore(statsGrid, tableContainer);
        } else {
            box.appendChild(statsGrid);
        }
    }

    // Update stats values
    document.getElementById('totalStudents').textContent = data.stats.totalStudents;
    document.getElementById('marksEntered').textContent = data.stats.marksEntered;
    document.getElementById('marksPending').textContent = data.stats.marksPending;
    document.getElementById('classAverage').textContent = (data.stats.classAverage || 0) + '%';
}

                // Table container creation if needed
                let tableContainer = document.querySelector('.marks-table-container');
                const emptyState = document.querySelector('.empty-state');
                if (!tableContainer) {
                    if (emptyState) emptyState.remove();
                    tableContainer = document.createElement('div');
                    tableContainer.className = 'marks-table-container';
                    tableContainer.innerHTML = '<form action="/index.php?url=markEntry" method="POST" id="marksForm">\n<input type="hidden" name="grade"/>\n<input type="hidden" name="class"/>\n<input type="hidden" name="term"/>\n<div class="table-wrapper"><table class="marks-table"><thead><tr><th>#</th><th>Reg Number</th><th>Student Name</th><th>Previous</th><th>Current Marks</th><th>Grade</th><th>Attendance</th><th>Status</th><th>Action</th></tr></thead><tbody></tbody></table></div><div class="action-buttons"><button type="button" class="btn btn-secondary" onclick="clearAllMarks()"><span class="btn-icon">🗑️</span><span class="btn-text">Clear All</span></button><button type="submit" class="btn btn-primary"><span class="btn-icon">✓</span><span class="btn-text">Submit Marks</span></button></div></form>';
                    document.querySelector('.box').appendChild(tableContainer);
                }
                const tbody = tableContainer.querySelector('.marks-table tbody');
                if (!tbody) return;
                tbody.innerHTML = '';
                (data.students || []).forEach((student, index) => {
                    let grade = '';
                    let gradeClass = '';
                    const cm = student.current_marks;
                    if (cm !== null && cm !== undefined && cm !== '') {
                        const m = Number(cm);
                        if (!isNaN(m)) {
                            if (m >= 75) { grade = 'A'; gradeClass = 'grade-a'; }
                            else if (m >= 65) { grade = 'B'; gradeClass = 'grade-b'; }
                            else if (m >= 55) { grade = 'C'; gradeClass = 'grade-c'; }
                            else if (m >= 35) { grade = 'S'; gradeClass = 'grade-s'; }
                            else { grade = 'W'; gradeClass = 'grade-w'; }
                        }
                    }
                    const tr = document.createElement('tr');
                    tr.className = 'student-row ' + (cm === null || cm === undefined || cm === '' ? 'pending' : 'completed');
                    const removeBtn = student.previous_marks !== null && student.previous_marks !== undefined && student.previous_marks !== '' ? 
                        '<button type="button" class="status-badge status-remove" onclick="removeMarks('+escapeHtml(student.id)+', \''+escapeHtml(student.name)+'\')">Remove</button>' : '';
                    tr.innerHTML = '<td>'+(index+1)+'</td>'+
                        '<td><span class="reg-badge">'+escapeHtml(student.reg_number)+'</span></td>'+
                        '<td><strong>'+escapeHtml(student.name)+'</strong></td>'+
                        '<td><span class="prev-marks">'+escapeHtml(student.previous_marks || '-')+'</span></td>'+
                        '<td><div class="marks-input-wrapper"><input type="number" name="marks['+escapeHtml(student.id)+']" class="marks-input" min="0" max="100" value="'+(cm !== null && cm !== undefined ? escapeHtml(cm) : '')+'" placeholder="0-100" data-student-id="'+escapeHtml(student.id)+'" onchange="calculateGrade(this)"><span class="marks-total">/ 100</span></div></td>'+
                        '<td><span class="grade-badge '+gradeClass+'" id="grade-'+escapeHtml(student.id)+'">'+(grade||'-')+'</span></td>'+
                        '<td><span class="attendance-badge">'+escapeHtml(student.attendance || '-')+'%</span></td>'+
                        '<td><span class="status-badge '+(cm !== null && cm !== undefined && cm !== '' ? 'status-entered':'status-pending')+'">'+(cm !== null && cm !== undefined && cm !== '' ? 'Entered':'Pending')+'</span></td>'+
                        '<td>'+removeBtn+'</td>';
                    tbody.appendChild(tr);
                });
                // Hidden inputs update
                const gradeInput = tableContainer.querySelector('input[name="grade"]');
                const classInput = tableContainer.querySelector('input[name="class"]');
                const termInput = tableContainer.querySelector('input[name="term"]');
                if (gradeInput) gradeInput.value = data.selectedGrade || '';
                if (classInput) classInput.value = data.selectedClass || '';
                if (termInput) termInput.value = data.selectedTerm || '';
            } catch (err) {
                console.error('updateStudentTable error:', err);
                alert('Load failed: '+ err.message);
            }
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            if (typeof text === 'object') {
                console.error('escapeHtml received object:', text);
                return String(text);
            }
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            const str = String(text);
            return str.replace(/[&<>"']/g, m => map[m]);
        }
    </script>