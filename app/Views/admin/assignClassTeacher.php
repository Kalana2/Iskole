<link rel="stylesheet" href="/css/assignClassTeacher/assignClassTeacher.css">

<div class="cs-wrapper">

    <?php if (!empty($flash)): ?>
        <script>
            alert("<?= addslashes($flash['text']) ?>");
        </script>
        <div class="cs-flash <?= $flash['type'] === 'error' ? 'err' : 'ok' ?>">
            <?= htmlspecialchars($flash['text']) ?>
        </div>
    <?php endif; ?>

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
                            $classId = (int) ($class['classID'] ?? 0);
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
                                    <form action="/index.php?url=classTeacher/assignTeacher" method="post"
                                        class="ct-assign-form">
                                        <input type="hidden" name="class_id" value="<?= $classId ?>">
                                        <select name="teacher_id" required>
                                            <option value="">-- Select Teacher --</option>
                                            <?php foreach ($teachersList as $teacher): ?>
                                                <?php
                                                $teacherId = (int) ($teacher['teacherID'] ?? 0);
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
                                        <form action="/index.php?url=classTeacher/removeTeacher" method="post"
                                            style="display: inline; margin: 0;">
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

</div>