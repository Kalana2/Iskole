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

    <?php
    $usedLeaveDays = isset($leaveBalance['used_leave_days']) ? (int)$leaveBalance['used_leave_days'] : 0;
    $annualLeaveLimit = isset($leaveBalance['annual_limit']) ? (int)$leaveBalance['annual_limit'] : 25;
    $remainingLeaveDays = isset($leaveBalance['remaining_leave_days']) ? (int)$leaveBalance['remaining_leave_days'] : max($annualLeaveLimit - $usedLeaveDays, 0);
    ?>

    <div style="margin:10px 0 14px;display:flex;gap:10px;flex-wrap:wrap;">
        <div style="flex:1 1 220px;padding:12px 14px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;">
            <div style="font-size:14px;color:#374151;margin-bottom:4px;">Used leave days this year</div>
            <div style="font-size:20px;color:#111827;font-weight:700;line-height:1.2;">
                <?= $usedLeaveDays ?>/<?= $annualLeaveLimit ?>
            </div>
        </div>

        <div style="flex:1 1 220px;padding:12px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;">
            <div style="font-size:14px;color:#166534;margin-bottom:4px;">Remaining leave days</div>
            <div style="font-size:20px;color:#15803d;font-weight:700;line-height:1.2;">
                <?= $remainingLeaveDays ?>
            </div>
        </div>
    </div>



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
                    <label for="date-from" style="color:#000;font-weight:400;">Date From</label>
                    <input type="date" name="dateFrom" id="date-from" required>
                    <small class="hint">Select the start date of your leave.</small>
                </div>

                <div class="field">
                    <label for="date-to" style="color:#000;font-weight:400;">Date To</label>
                    <input type="date" name="dateTo" id="date-to" required>
                    <small class="hint">Select the end date of your leave.</small>
                </div>


                <div class="field span-2">
                    <label style="color:#000;font-weight:400;">Type of Leave</label>

                    <div class="leave-type-group">
                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="medical" required>
                            <span class="tick-box"></span>
                            <span class="tick-text" style="color:#000;font-weight:400;">Medical Leave</span>
                        </label>

                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="personal" required>
                            <span class="tick-box"></span>
                            <span class="tick-text" style="color:#000;font-weight:400;">Personal Leave</span>
                        </label>

                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="duty" required>
                            <span class="tick-box"></span>
                            <span class="tick-text" style="color:#000;font-weight:400;">Duty Leave</span>
                        </label>
                    </div>

                    <small class="hint">Choose the type of leave you are requesting.</small>
                </div>


                <div class="field span-2">
                    <label for="reason" style="color:#000;font-weight:400;">Reason</label>
                    <textarea
                        name="reason"
                        id="reason"
                        rows="4"
                        maxlength="250"
                        placeholder="Enter reason..."></textarea>

                    <small class="hint">
                        <span id="reasonCount">0</span> / 250 characters
                    </small>
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
        const formSection = document.currentScript.previousElementSibling;
        if (!formSection) return;

        // Existing validation
        const formEl = formSection.querySelector('form');
        formEl?.addEventListener('submit', (e) => {
            if (!formEl.checkValidity()) {
                e.preventDefault();
                const invalid = formEl.querySelector(':invalid');
                invalid?.focus();
            }
        });

        // ✅ Character counter for Reason
        const reason = $('#reason', formSection);
        const counter = $('#reasonCount', formSection);
        const max = reason?.getAttribute('maxlength') || 0;

        if (reason && counter) {
            const updateCount = () => {
                counter.textContent = reason.value.length;
            };

            reason.addEventListener('input', updateCount);
            updateCount(); // initial
        }
    })();
</script>