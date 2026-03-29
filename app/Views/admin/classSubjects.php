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

    <!-- ===================== CLASSES ===================== -->
    <div class="cs-section">
        <div class="cs-head">
            <h2>Classes</h2>
            <p>Existing classes in the system (6A, 6B ...)</p>
        </div>

        <div class="cs-content">
            <div class="cs-grid">
                <?php
                $classesList = isset($classes) && is_array($classes) ? $classes : [];
                ?>

                <?php if (empty($classesList)): ?>
                    <div class="cs-empty">No classes found.</div>
                <?php endif; ?>

                <?php foreach ($classesList as $c): ?>
                    <?php
                    $grade = trim($c['grade'] ?? '');
                    // ✅ DB column is `class` (A/B)
                    $sec = strtoupper(trim($c['class'] ?? ''));
                    $label = $grade . $sec; // 6A, 6B ...
                    ?>
                    <div class="cs-chip">
                        <span><?= htmlspecialchars($label) ?></span>

                        <form action="/index.php?url=classSubject/deleteClass" method="post">
                            <input type="hidden" name="class_id" value="<?= (int) ($c['classID'] ?? 0) ?>">
                            <button class="cs-del" type="submit"
                                onclick="return confirm('Delete class <?= htmlspecialchars($label) ?> ?')">✕</button>
                        </form>
                    </div>
                <?php endforeach; ?>

            </div>

            <hr class="cs-hr">

            <form class="cs-form" action="/index.php?url=classSubject/createClass" method="post">
                <h3>Create New Class</h3>

                <div class="cs-row">
                    <div class="cs-field">
                        <label>Grade</label>
                        <select name="grade" required>
                            <?php for ($g = 1; $g <= 13; $g++): ?>
                                <option value="<?= $g ?>"><?= $g ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="cs-field">
                        <label>Section (A/B/C...)</label>
                        <input type="text" name="section" maxlength="2" placeholder="A" required>
                    </div>

                    <button class="cs-btn" type="submit">Create</button>
                </div>


            </form>
        </div>
    </div>

    <!-- ===================== SUBJECTS ===================== -->
    <div class="cs-section">
        <div class="cs-head">
            <h2>Subjects</h2>
            <p>Subjects relevant to grade (6,7,8,9)</p>
        </div>

        <div class="cs-content">
            <?php
            $subjectsList = isset($subjects) && is_array($subjects) ? $subjects : [];
            $byGrade = [];
            foreach ($subjectsList as $s) {
                $g = (int) ($s['grade'] ?? 0);
                $byGrade[$g][] = $s;
            }
            ksort($byGrade);
            ?>

            <?php if (empty($byGrade)): ?>
                <div class="cs-empty">No subjects found.</div>
            <?php endif; ?>

            <?php foreach ($byGrade as $g => $items): ?>

                <div class="cs-grade">
                    <!-- <h3>Grade <?= (int) $g ?></h3> -->
                    <div class="cs-grid">
                        <?php foreach ($items as $s): ?>
                            <div class="cs-chip">
                                <span><?= htmlspecialchars($s['subjectName'] ?? '') ?></span>

                                <form action="/index.php?url=classSubject/deleteSubject" method="post">
                                    <input type="hidden" name="subject_id" value="<?= (int) $s['subjectID'] ?>">
                                    <button class="cs-del" type="submit"
                                        onclick="return confirm('Delete subject <?= htmlspecialchars($s['subjectName'] ?? '') ?> ?')">
                                        ✕
                                    </button>

                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <hr class="cs-hr">
            <?php endforeach; ?>

            <form class="cs-form" action="/index.php?url=classSubject/createSubject" method="post">
                <h3>Add New Subject</h3>

                <div class="cs-row">
                    <div class="cs-field">
                        <label>Subject Name</label>
                        <input type="text" name="subjectName" placeholder="Mathematics" required>
                    </div>

                    <button class="cs-btn" type="submit">Add</button>
                </div>
            </form>
        </div>
    </div>

</div>