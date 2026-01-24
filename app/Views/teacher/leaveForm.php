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



    <?php if (!empty($_SESSION['leave_msg'])): $msg = $_SESSION['leave_msg'];
        unset($_SESSION['leave_msg']); ?>
        <div style="margin:10px 0;padding:12px;border-radius:6px;
              border:1px solid <?= $msg['type'] === 'error' ? 'red' : 'green' ?>;
              color:<?= $msg['type'] === 'error' ? 'red' : 'green' ?>;">
            <?= htmlspecialchars($msg['text']) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form action="/index.php?url=leave/submit" method="post" novalidate>
            <div class="form-grid">
                <div class="field">
                    <label for="date-from">Date From</label>
                    <input type="date" name="dateFrom" id="date-from" required>
                    <small class="hint">Select the start date of your leave.</small>
                </div>

                <div class="field">
                    <label for="date-to">Date To</label>
                    <input type="date" name="dateTo" id="date-to" required>
                    <small class="hint">Select the end date of your leave.</small>
                </div>


                <div class="field span-2">
                    <label>Type of Leave</label>

                    <div class="leave-type-group">
                        <label class="leave-tick">
                            <input type="radio" name="leaveType" value="medical" required>
                            <span class="tick-box"></span>
                            <span class="tick-text">Medical Leave</span>
                        </label>

                        <label class="leave-tick">
                            <input type="radio" name="leaveType" value="personal" required>
                            <span class="tick-box"></span>
                            <span class="tick-text">Personal Leave</span>
                        </label>

                        <label class="leave-tick">
                            <input type="radio" name="leaveType" value="duty" required>
                            <span class="tick-box"></span>
                            <span class="tick-text">Duty Leave</span>
                        </label>
                    </div>

                    <small class="hint">Choose the type of leave you are requesting.</small>
                </div>


                <div class="field span-2">
                    <label for="reason">Reason</label>
                    <textarea
                        name="reason"
                        id="reason"
                        rows="4"
                        placeholder="Enter reason..."></textarea>
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