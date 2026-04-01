<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fallback logic in case the view is rendered directly without variables passed
if (!isset($dbClasses) || !isset($dbSubjects)) {
    require_once __DIR__ . '/../../Model/materialModel.php';
    try {
        $model = new Material();
        $dbClasses = $model->getAllClasses() ?? [];
        $dbSubjects = $model->getAllSubjects() ?? [];
    } catch (Exception $e) {
        error_log("Failed to load dimensions using the Material model: " . $e->getMessage());
        $dbClasses = [];
        $dbSubjects = [];
    }
}

// Map unique grades dynamically from the combined dbClasses rows
$dbGrades = array_unique(array_column($dbClasses, 'grade'));
sort($dbGrades);
?>

<link rel="stylesheet" href="/css/addNewUser/addNewUser.css">

<section class="mp-management" aria-labelledby="mgmt-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="mgmt-form-title">Upload Teaching Materials</h2>
            <p class="subtitle">Share lesson plans and worksheets with students</p>
        </div>
    </header>

    <div class="card">
        <form action="/teacher/materials?action=create" method="post" enctype="multipart/form-data" id="material-upload-form">
            <div class="form-grid">
                <div class="field">
                    <label for="Grade">Select Grade</label>
                    <select name="grade" id="Grade" required>
                        <option value="" selected disabled>Select Grade</option>
                        <?php foreach ($dbGrades as $g): ?>
                            <option value="<?= htmlspecialchars($g) ?>">Grade <?= htmlspecialchars(str_pad($g, 2, '0', STR_PAD_LEFT)) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="hint">Choose the grade level.</small>
                </div>

                <div class="field">
                    <label for="Class">Select Class</label>
                    <select name="class" id="Class" required>
                        <option value="" selected disabled>Select Class</option>
                        <!-- Options dynamically populated by JS based on Grade selection -->
                    </select>
                    <small class="hint">Choose the class section.</small>
                </div>

                <div class="field span-2">
                    <label for="subject">Select Subject</label>
                    <select name="subject" id="subject" required>
                        <option value="" selected disabled>Select Subject</option>
                        <?php foreach ($dbSubjects as $s): ?>
                            <option value="<?= htmlspecialchars($s['subjectID']) ?>"><?= htmlspecialchars($s['subjectName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="hint">Choose the subject area.</small>
                </div>

                <div class="field span-2">
                    <label for="material-title">Material Title</label>
                    <input type="text" name="material-title" id="material-title" placeholder="Enter the material title" required maxlength="120">
                    <small class="hint"><span id="title-count">0</span>/120</small>
                </div>

                <div class="field span-2">
                    <label for="material-description">Description</label>
                    <textarea name="material-description" id="material-description" rows="6" placeholder="Write a brief description of the material..." required maxlength="500"></textarea>
                    <small class="hint" id="desc-count">0/500</small>
                </div>

                <div class="field span-2">
                    <label for="file-upload">Upload File</label>
                    <input type="file" name="file-upload" id="file-upload" required>
                    <small class="hint">Upload PDF, DOC, DOCX, PPT, PPTX, or image files (max 10MB).</small>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-ghost" type="reset">Clear</button>
                <button class="btn btn-primary" type="submit" name="submit">Publish Material</button>
            </div>
        </form>
    </div>
</section>

<script>
    (function() {
        const allClasses = <?= json_encode($dbClasses) ?>;
        const gradeSelect = document.getElementById('Grade');
        const classSelect = document.getElementById('Class');

        if (gradeSelect && classSelect) {
            gradeSelect.addEventListener('change', function() {
                const selectedGrade = String(this.value);
                classSelect.innerHTML = '<option value="" selected disabled>Select Class</option>';
                allClasses.forEach(item => {
                    if (String(item.grade) === selectedGrade) {
                        const opt = document.createElement('option');
                        opt.value = item.class;
                        opt.textContent = 'Class ' + item.class;
                        classSelect.appendChild(opt);
                    }
                });
            });
        }

        const $ = (s, ctx = document) => ctx.querySelector(s);
        const formSection = document.currentScript.previousElementSibling;
        if (!formSection) return;
        const title = $('#material-title', formSection);
        const titleCount = $('#title-count', formSection);
        const desc = $('#material-description', formSection);
        const descCount = $('#desc-count', formSection);

        const updateCounts = () => {
            if (title && titleCount) titleCount.textContent = String(title.value.length);
            if (desc && descCount) descCount.textContent = `${desc.value.length}/${desc.maxLength}`;
        };
        updateCounts();
        [title, desc].forEach(el => el && el.addEventListener('input', updateCounts));

        // Lightweight client-side validation
        const formEl = formSection.querySelector('form');
        formEl?.addEventListener('submit', (e) => {
            if (!formEl.checkValidity()) {
                e.preventDefault();
                const invalid = formEl.querySelector(':invalid');
                invalid?.focus();
            }
        });

        // Uploading via Fetch API
        formEl.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formEl);

            const submitButton = formEl.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';

            fetch('/teacher/materials?action=create', {
                method: 'POST',
                body: formData // sends form data directly without jsonification
            }).then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            }).then(result => {
                if (result.success) {
                    alert(result.message || 'Material uploaded successfully!');
                    formEl.reset();
                    updateCounts();
                    location.reload();
                } else {
                    alert(result.message || 'Failed to upload material. Please try again.');
                }
            }).catch(error => {
                console.error('Upload error:', error);
                alert('An error occurred while uploading. Please check the console for details.');
            }).finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Publish Material';
            });
        })
    })();
</script>