<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/createAnnouncement.php
?>
<link rel="stylesheet" href="/css/addNewUser/addNewUser.css">

<section class="mp-management" aria-labelledby="mgmt-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="mgmt-form-title">Create Announcement</h2>
            <p class="subtitle">Publish updates to specific groups</p>
        </div>
    </header>

    <div class="card">
        <form action="" method="post" novalidate>
            <div class="form-grid">
                <div class="field span-2">
                    <label for="targetAudience">Target Audience</label>
                    <select name="group" id="targetAudience" required>
                        <option value="" selected disabled>Select Audience</option>
                        <option value="all">Management Panel, Teachers &amp; Students</option>
                        <option value="mp">Management Panel</option>
                        <option value="teachers">Teachers</option>
                        <option value="mp_teachers">Management Panel &amp; Teachers</option>
                        <option value="teachers_students">Teachers &amp; Students</option>
                        <option value="students">Students</option>
                    </select>
                    <small class="hint">Choose who should receive this announcement.</small>
                </div>

                <div class="field span-2">
                    <label for="announcementTitle">Announcement Title</label>
                    <input type="text" name="title" id="announcementTitle" placeholder="Enter the announcement title" required maxlength="120">
                    <small class="hint"><span id="title-count">0</span>/120</small>
                </div>

                <div class="field span-2">
                    <label for="announcementMessage">Message</label>
                    <textarea name="message" id="announcementMessage" rows="8" placeholder="Type your announcement message here" required maxlength="1000"></textarea>
                    <small class="hint" id="msg-count">0/1000</small>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-ghost" type="reset">Clear</button>
                <button class="btn btn-primary" type="submit">Publish Announcement</button>
            </div>
        </form>
    </div>
</section>

<script>
    (function(){
        const $ = (s, ctx=document) => ctx.querySelector(s);
        const formSection = document.currentScript.previousElementSibling; // section.mp-management
        if (!formSection) return;
        const title = $('#announcementTitle', formSection);
        const titleCount = $('#title-count', formSection);
        const msg = $('#announcementMessage', formSection);
        const msgCount = $('#msg-count', formSection);

        const updateCounts = () => {
            if (title && titleCount) titleCount.textContent = String(title.value.length);
            if (msg && msgCount) msgCount.textContent = `${msg.value.length}/${msg.maxLength}`;
        };
        updateCounts();
        [title, msg].forEach(el => el && el.addEventListener('input', updateCounts));

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
