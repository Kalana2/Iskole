    <?php
    // The controller injects: $teacherInfo, $grades, $classes, $terms,
    // $selectedGrade, $selectedClass, $selectedTerm, $selectedExamType,
    // $students, statistics and optional $message.

    // Defensive defaults to avoid PHP notices when called directly.
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
        return isset($s['current_marks']) && $s['current_marks'] !== null && $s['current_marks'] !== '';
    }));
    $marksPending = $marksPending ?? ($totalStudents - $marksEntered);
    $completionPercentage = $completionPercentage ?? ($totalStudents > 0 ? round(($marksEntered / $totalStudents) * 100) : 0);
    $classAverage = $classAverage ?? 0;
    if (empty($classAverage)) {
        $enteredMarks = array_filter(array_column($students, 'current_marks'), function ($m) {
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
                <form action="/markEntry" method="POST" class="filter-form" id="filterForm">
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
                    <form action="/markEntry/submit" method="POST" id="marksForm">
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
                    <p class="empty-description">Please select grade, class, term, and exam type from the filters above to load student list.</p>
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
        document.getElementById('marksForm')?.addEventListener('submit', function(e) {
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

        // Auto-save functionality (every 2 minutes)
        setInterval(function() {
            console.log('Auto-saving draft...');
            // TODO: Implement auto-save via AJAX
        }, 120000);
    </script>