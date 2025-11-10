<?php ?>

<link rel="stylesheet" href="/css/addNewUser/addNewUser.css">

<section class="mp-management" aria-labelledby="mgmt-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="mgmt-form-title">Upload Teaching Materials</h2>
            <p class="subtitle">Share lesson plans and worksheets with students</p>
        </div>
    </header>

    <div class="card">
        <form action="../Teacher/teacherDashboard.php" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-grid">
                <div class="field">
                    <label for="Grade">Select Grade</label>
                    <select name="grade" id="Grade" required>
                        <option value="" selected disabled>Select Grade</option>
                        <option value="6">Grade 06</option>
                        <option value="7">Grade 07</option>
                        <option value="8">Grade 08</option>
                        <option value="9">Grade 09</option>
                    </select>
                    <small class="hint">Choose the grade level.</small>
                </div>

                <div class="field">
                    <label for="Class">Select Class</label>
                    <select name="class" id="Class" required>
                        <option value="" selected disabled>Select Class</option>
                        <option value="A">Class A</option>
                        <option value="B">Class B</option>
                    </select>
                    <small class="hint">Choose the class section.</small>
                </div>

                <div class="field span-2">
                    <label for="subject">Select Subject</label>
                    <select name="subject" id="subject" required>
                        <option value="" selected disabled>Select Subject</option>
                        <option value="1">Maths</option>
                        <option value="2">Science</option>
                        <option value="3">English</option>
                        <option value="4">History</option>
                        <option value="5">Geography</option>
                        <option value="6">Aesthetics</option>
                        <option value="7">PTS</option>
                        <option value="8">Religion</option>
                        <option value="9">Health and Physical Education</option>
                        <option value="10">Tamil</option>
                        <option value="11">Citizenship Education</option>
                        <option value="12">Sinhala</option>
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
    })();
</script>