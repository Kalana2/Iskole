<?php
// filepath: /d:/Semester 4/SCS2202 - Group Project/Iskole/app/Views/teacher/leaveForm.php
?>
<link rel="stylesheet" href="/css/addNewUser/addNewUser.css">

<!--Nav6 : leave-request-->
<section class="mp-management tab-panel" aria-labelledby="leave-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="leave-form-title">Request For Leaves</h2>
            <p class="subtitle">Fill this leave request form</p>
        </div>
    </header>

    <div class="card">
        <form action="" method="post" novalidate>
            <div class="form-grid">
                <div class="field">
                    <label for="date-from">Date From</label>
                    <input type="date" name="date-from" id="date-from" required>
                    <small class="hint">Select the start date of your leave.</small>
                </div>

                <div class="field">
                    <label for="date-to">Date To</label>
                    <input type="date" name="date-to" id="date-to" required>
                    <small class="hint">Select the end date of your leave.</small>
                </div>

                <div class="field span-2">
                    <label>Type of Leave</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input
                                type="radio"
                                name="leave-type"
                                id="medical-leave"
                                value="medical"
                                required>
                            <label for="medical-leave">Medical Leave</label>
                        </div>
                        <div class="radio-option">
                            <input
                                type="radio"
                                name="leave-type"
                                id="personal-leave"
                                value="personal"
                                required>
                            <label for="personal-leave">Personal Leave</label>
                        </div>
                        <div class="radio-option">
                            <input
                                type="radio"
                                name="leave-type"
                                id="duty-leave"
                                value="duty"
                                required>
                            <label for="duty-leave">Duty Leave</label>
                        </div>
                    </div>
                    <small class="hint">Choose the type of leave you are requesting.</small>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-ghost" type="reset">Reset</button>
                <button class="btn btn-primary" type="submit">Submit Request</button>
            </div>
        </form>
    </div>
</section>

<script>
    (function() {
        const $ = (s, ctx = document) => ctx.querySelector(s);
        const formSection = document.currentScript.previousElementSibling; // section.mp-management
        if (!formSection) return;

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