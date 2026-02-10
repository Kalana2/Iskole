<link rel="stylesheet" href="/css/classSubject/classSubject.css">

<div class="cs-wrapper">

    <?php if (!empty($flash)): ?>
        <script>
            alert("<?= addslashes($flash['text']) ?>");
        </script>
        <div class="cs-flash <?= $flash['type'] === 'error' ? 'err' : 'ok' ?>">
            <?= htmlspecialchars($flash['text']) ?>
        </div>
    <?php endif; ?>

    <!-- ===================== ASSIGN CLASS TEACHER ===================== -->
    <div class="cs-section">
        <div class="cs-head">
            <h2>Assign Class Teachers</h2>
            <p>Assign a class teacher to each class</p>
        </div>

        <div class="cs-content">
            <?php
            $classList = isset($classesWithTeachers) && is_array($classesWithTeachers) ? $classesWithTeachers : [];
            $teachersList = isset($teachers) && is_array($teachers) ? $teachers : [];
            ?>

            <?php if (empty($classList)): ?>
                <div class="cs-empty">No classes found. Please create classes first.</div>
            <?php else: ?>
                <table class="ct-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Current Class Teacher</th>
                            <th>Assign New Teacher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classList as $class): ?>
                            <?php
                            $grade = trim($class['grade'] ?? '');
                            $section = strtoupper(trim($class['class'] ?? ''));
                            $classLabel = $grade . $section;
                            $currentTeacher = $class['teacherName'] ?? 'Not Assigned';
                            $classId = (int)($class['classID'] ?? 0);
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($classLabel) ?></strong></td>
                                <td>
                                    <?php if ($currentTeacher === 'Not Assigned'): ?>
                                        <span class="ct-no-teacher">Not Assigned</span>
                                    <?php else: ?>
                                        <span class="ct-teacher-name"><?= htmlspecialchars($currentTeacher) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="/index.php?url=classTeacher/assignTeacher" method="post" class="ct-assign-form">
                                        <input type="hidden" name="class_id" value="<?= $classId ?>">
                                        <select name="teacher_id" required>
                                            <option value="">-- Select Teacher --</option>
                                            <?php foreach ($teachersList as $teacher): ?>
                                                <?php
                                                $teacherId = (int)($teacher['teacherID'] ?? 0);
                                                $teacherName = htmlspecialchars($teacher['name'] ?? 'Unknown');
                                                $subject = $teacher['subjectName'] ? ' (' . htmlspecialchars($teacher['subjectName']) . ')' : '';
                                                $teacherClassId = $teacher['classID'] ?? null;
                                                
                                                // Check if this teacher is already assigned to another class
                                                $isAssignedElsewhere = $teacherClassId && $teacherClassId != $classId;
                                                ?>
                                                <option value="<?= $teacherId ?>" <?= $isAssignedElsewhere ? 'disabled' : '' ?>>
                                                    <?= $teacherName . $subject ?>
                                                    <?= $isAssignedElsewhere ? ' - Already assigned' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="cs-btn ct-assign-btn">Assign</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($currentTeacher !== 'Not Assigned'): ?>
                                        <form action="/index.php?url=classTeacher/removeTeacher" method="post" style="display: inline; margin: 0;">
                                            <input type="hidden" name="class_id" value="<?= $classId ?>">
                                            <button type="submit" class="ct-remove-btn" 
                                                onclick="return confirm('Remove class teacher from <?= htmlspecialchars($classLabel) ?>?')">
                                                ✕ Remove
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="ct-no-action">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===================== TEACHER LIST ===================== -->
    <div class="cs-section">
        <div class="cs-head">
            <h2>Available Teachers</h2>
            <p>List of all active teachers in the system</p>
        </div>

        <div class="cs-content">
            <?php if (empty($teachersList)): ?>
                <div class="cs-empty">No teachers found.</div>
            <?php else: ?>
                <div class="ct-teacher-grid">
                    <?php foreach ($teachersList as $teacher): ?>
                        <?php
                        $teacherName = htmlspecialchars($teacher['name'] ?? 'Unknown');
                        $subject = $teacher['subjectName'] ? htmlspecialchars($teacher['subjectName']) : 'N/A';
                        $teacherClassId = $teacher['classID'] ?? null;
                        $isAssigned = !empty($teacherClassId);
                        
                        // Find class label if assigned
                        $assignedClass = '';
                        if ($isAssigned) {
                            foreach ($classList as $c) {
                                if ($c['classID'] == $teacherClassId) {
                                    $assignedClass = trim($c['grade']) . strtoupper(trim($c['class']));
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="ct-teacher-card <?= $isAssigned ? 'ct-assigned' : '' ?>">
                            <div class="ct-teacher-name"><?= $teacherName ?></div>
                            <div class="ct-teacher-subject">Subject: <?= $subject ?></div>
                            <?php if ($isAssigned): ?>
                                <div class="ct-teacher-status">Class Teacher: <?= htmlspecialchars($assignedClass) ?></div>
                            <?php else: ?>
                                <div class="ct-teacher-status-available">Available</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
    .ct-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .ct-table th,
    .ct-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .ct-table th {
        background-color: var(--primary-color, #4CAF50);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    .ct-table tr:hover {
        background-color: #f5f5f5;
    }

    .ct-table tr:last-child td {
        border-bottom: none;
    }

    .ct-no-teacher {
        color: #999;
        font-style: italic;
    }

    .ct-teacher-name {
        color: var(--primary-color, #4CAF50);
        font-weight: 600;
    }

    .ct-assign-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .ct-assign-form select {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .ct-assign-btn {
        padding: 8px 16px !important;
        font-size: 0.9em !important;
        white-space: nowrap;
    }

    .ct-remove-btn {
        background-color: #f44336 !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        font-size: 0.9em !important;
        transition: all 0.3s ease !important;
        font-weight: 500 !important;
    }

    .ct-remove-btn:hover {
        background-color: #d32f2f !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(244, 67, 54, 0.3);
    }

    .ct-no-action {
        color: #ccc;
        font-size: 1.2em;
    }

    .ct-teacher-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .ct-teacher-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .ct-teacher-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .ct-teacher-card.ct-assigned {
        border-color: var(--primary-color, #4CAF50);
        background-color: #f1f8f4;
    }

    .ct-teacher-card .ct-teacher-name {
        font-size: 1.1em;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .ct-teacher-card .ct-teacher-subject {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 8px;
    }

    .ct-teacher-card .ct-teacher-status {
        font-size: 0.85em;
        color: var(--primary-color, #4CAF50);
        font-weight: 600;
        padding: 4px 8px;
        background: white;
        border-radius: 4px;
        display: inline-block;
        margin-top: 5px;
    }

    .ct-teacher-card .ct-teacher-status-available {
        font-size: 0.85em;
        color: #999;
        font-style: italic;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .ct-table {
            font-size: 0.9em;
        }

        .ct-assign-form {
            flex-direction: column;
            align-items: stretch;
        }

        .ct-assign-btn {
            width: 100%;
        }

        .ct-teacher-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
