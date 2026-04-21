<?php
$editLeave = $editLeave ?? null;
$isEdit = !empty($editLeave);
?>
<link rel="stylesheet" href="/css/addNewUser/addNewUser.css">

<section class="mp-management tab-panel" aria-labelledby="leave-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="leave-form-title"><?= $isEdit ? 'Edit Leave Request' : 'Request For Leaves' ?></h2>
            <p class="subtitle"><?= $isEdit ? 'Update your pending leave request' : 'Fill this leave request form' ?></p>
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

    <?php if (!empty($_SESSION['leave_msg'])): $msg = $_SESSION['leave_msg']; unset($_SESSION['leave_msg']); ?>
        <div style="margin:10px 0;padding:12px;border-radius:6px;
              border:1px solid <?= $msg['type'] === 'error' ? 'red' : 'green' ?>;
              color:<?= $msg['type'] === 'error' ? 'red' : 'green' ?>;">
            <?= htmlspecialchars($msg['text']) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form action="/index.php?url=leave/<?= $isEdit ? 'update' : 'submit' ?>" method="post" novalidate>
            <?php if ($isEdit): ?>
                <input type="hidden" name="leave_id" value="<?= htmlspecialchars($editLeave['id'] ?? '') ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="field">
                    <label for="date-from" style="color:#000;font-weight:400;">Date From</label>
                    <input type="date" name="dateFrom" id="date-from" required
                           value="<?= htmlspecialchars($editLeave['dateFrom'] ?? '') ?>">
                    <small class="hint">Select the start date of your leave.</small>
                </div>

                <div class="field">
                    <label for="date-to" style="color:#000;font-weight:400;">Date To</label>
                    <input type="date" name="dateTo" id="date-to" required
                           value="<?= htmlspecialchars($editLeave['dateTo'] ?? '') ?>">
                    <small class="hint">Select the end date of your leave.</small>
                </div>

                <div class="field span-2">
                    <label style="color:#000;font-weight:400;">Type of Leave</label>

                    <div class="leave-type-group">
                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="medical" required
                                <?= (($editLeave['leaveType'] ?? '') === 'medical') ? 'checked' : '' ?>>
                            <span class="tick-box"></span>
                            <span class="tick-text" style="color:#000;font-weight:400;">Medical Leave</span>
                        </label>

                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="personal" required
                                <?= (($editLeave['leaveType'] ?? '') === 'personal') ? 'checked' : '' ?>>
                            <span class="tick-box"></span>
                            <span class="tick-text" style="color:#000;font-weight:400;">Personal Leave</span>
                        </label>

                        <label class="leave-tick" style="color:#000;font-weight:400;">
                            <input type="radio" name="leaveType" value="duty" required
                                <?= (($editLeave['leaveType'] ?? '') === 'duty') ? 'checked' : '' ?>>
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
                        placeholder="Enter reason..."><?= htmlspecialchars($editLeave['reason'] ?? '') ?></textarea>

                    <small class="hint">
                        <span id="reasonCount">0</span> / 250 characters
                    </small>
                </div>
            </div>

            <div class="form-actions">
                <?php if ($isEdit): ?>
                    <a href="/index.php?url=teacher&tab=Leave"
                       class="btn btn-ghost"
                       style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">
                        Cancel Edit
                    </a>
                    <button class="btn btn-primary" type="submit">Update Request</button>
                <?php else: ?>
                    <button class="btn btn-ghost" type="reset">Reset</button>
                    <button class="btn btn-primary" type="submit">Submit Request</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</section>
<script>
(function() {
    const $ = (s, ctx = document) => ctx.querySelector(s);
    const formSection = document.currentScript.previousElementSibling;
    if (!formSection) return;

    const formEl = formSection.querySelector('form');
    const dateFrom = $('#date-from', formSection);
    const dateTo = $('#date-to', formSection);

    const validateDates = () => {
        if (!dateFrom || !dateTo) return true;

        dateTo.setCustomValidity('');

        const fromVal = dateFrom.value;
        const toVal = dateTo.value;

        if (!fromVal || !toVal) return true;

        
        if (toVal < fromVal) {
            dateTo.setCustomValidity('Date To cannot be before Date From.');
            return false;
        }

        return true;
    };

  
    dateFrom?.addEventListener('change', () => {
        if (!dateFrom.value) return;

        
        dateTo.min = dateFrom.value;

        validateDates();
    });

    dateTo?.addEventListener('change', validateDates);

    formEl?.addEventListener('submit', (e) => {
        if (!formEl.checkValidity() || !validateDates()) {
            e.preventDefault();
            const invalid = formEl.querySelector(':invalid');
            invalid?.focus();
            invalid?.reportValidity?.();
        }
    });
})();
</script>