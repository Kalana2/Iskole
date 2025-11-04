    <?php
    // filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\markEntry.php

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

    $terms = [
        ['value' => '1', 'label' => 'Term 1'],
        ['value' => '2', 'label' => 'Term 2'],
        ['value' => '3', 'label' => 'Term 3']
    ];

    $examTypes = [
        ['value' => 'midterm', 'label' => 'Mid-Term Examination'],
        ['value' => 'final', 'label' => 'Final Examination'],
        ['value' => 'monthly', 'label' => 'Monthly Test'],
        ['value' => 'class', 'label' => 'Class Test']
    ];

    // Sample selected values (when form is submitted)
    $selectedGrade = '10';
    $selectedClass = 'A';
    $selectedTerm = '1';
    $selectedExamType = 'midterm';

    // Sample student data for Grade 10-A
    $students = [
        [
            'id' => 1,
            'reg_number' => '2024001',
            'name' => 'Amal Perera',
            'current_marks' => 85,
            'previous_marks' => 78,
            'attendance' => 95
        ],
        [
            'id' => 2,
            'reg_number' => '2024002',
            'name' => 'Nimal Silva',
            'current_marks' => 72,
            'previous_marks' => 70,
            'attendance' => 88
        ],
        [
            'id' => 3,
            'reg_number' => '2024003',
            'name' => 'Kumari Fernando',
            'current_marks' => 91,
            'previous_marks' => 89,
            'attendance' => 98
        ],
        [
            'id' => 4,
            'reg_number' => '2024004',
            'name' => 'Saman Rajapaksa',
            'current_marks' => 68,
            'previous_marks' => 65,
            'attendance' => 85
        ],
        [
            'id' => 5,
            'reg_number' => '2024005',
            'name' => 'Dilini Wickramasinghe',
            'current_marks' => 88,
            'previous_marks' => 85,
            'attendance' => 92
        ],
        [
            'id' => 6,
            'reg_number' => '2024006',
            'name' => 'Kasun Bandara',
            'current_marks' => null,
            'previous_marks' => 75,
            'attendance' => 90
        ],
        [
            'id' => 7,
            'reg_number' => '2024007',
            'name' => 'Chamari Jayawardena',
            'current_marks' => 79,
            'previous_marks' => 82,
            'attendance' => 87
        ],
        [
            'id' => 8,
            'reg_number' => '2024008',
            'name' => 'Tharindu Gunasekara',
            'current_marks' => null,
            'previous_marks' => 70,
            'attendance' => 83
        ],
        [
            'id' => 9,
            'reg_number' => '2024009',
            'name' => 'Nethmi Rathnayake',
            'current_marks' => 94,
            'previous_marks' => 92,
            'attendance' => 100
        ],
        [
            'id' => 10,
            'reg_number' => '2024010',
            'name' => 'Isuru Mendis',
            'current_marks' => 76,
            'previous_marks' => 73,
            'attendance' => 91
        ]
    ];

    // Calculate statistics
    $totalStudents = count($students);
    $marksEntered = count(array_filter($students, function ($s) {
        return $s['current_marks'] !== null;
    }));
    $marksPending = $totalStudents - $marksEntered;
    $completionPercentage = $totalStudents > 0 ? round(($marksEntered / $totalStudents) * 100) : 0;

    // Calculate class average for entered marks
    $enteredMarks = array_filter(array_column($students, 'current_marks'), function ($m) {
        return $m !== null;
    });
    $classAverage = !empty($enteredMarks) ? round(array_sum($enteredMarks) / count($enteredMarks), 2) : 0;
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
                            <label for="term" class="form-label">Term</label>
                            <select name="term" id="term" class="form-select" required>
                                <option value="">Select Term</option>
                                <?php foreach ($terms as $term): ?>
                                    <option value="<?php echo $term['value']; ?>"
                                        <?php echo $selectedTerm === $term['value'] ? 'selected' : ''; ?>>
                                        <?php echo $term['label']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="examType" class="form-label">Exam Type</label>
                            <select name="examType" id="examType" class="form-select" required>
                                <option value="">Select Exam Type</option>
                                <?php foreach ($examTypes as $exam): ?>
                                    <option value="<?php echo $exam['value']; ?>"
                                        <?php echo $selectedExamType === $exam['value'] ? 'selected' : ''; ?>>
                                        <?php echo $exam['label']; ?>
                                    </option>
                                <?php endforeach; ?>
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
                <div class="selection-summary">
                    <div class="summary-badge">
                        <span class="summary-icon">📋</span>
                        <span class="summary-text">
                            Showing: <strong>Grade <?php echo $selectedGrade; ?>-<?php echo $selectedClass; ?></strong> •
                            <strong><?php echo $terms[intval($selectedTerm) - 1]['label']; ?></strong> •
                            <strong><?php echo $examTypes[array_search($selectedExamType, array_column($examTypes, 'value'))]['label']; ?></strong>
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

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-header">
                        <span class="progress-label">Completion Progress</span>
                        <span class="progress-percentage"><?php echo $completionPercentage; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $completionPercentage; ?>%"></div>
                    </div>
                </div>

                <!-- Marks Entry Table -->
                <div class="marks-table-container">
                    <form action="#" method="POST" id="marksForm">
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
                            <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                                <span class="btn-icon">💾</span>
                                <span class="btn-text">Save Draft</span>
                            </button>
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
            e.preventDefault();

            const inputs = document.querySelectorAll('.marks-input');
            let allFilled = true;

            inputs.forEach(input => {
                if (!input.value || input.value === '') {
                    allFilled = false;
                }
            });

            if (!allFilled) {
                if (!confirm('Some marks are still pending. Do you want to submit anyway?')) {
                    return false;
                }
            }

            // TODO: Implement actual form submission
            alert('Marks submitted successfully! (This is a demo)');
            console.log('Form submitted');
        });

        // Auto-save functionality (every 2 minutes)
        setInterval(function() {
            console.log('Auto-saving draft...');
            // TODO: Implement auto-save via AJAX
        }, 120000);
    </script>