<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/requests.php
?>
<link rel="stylesheet" href="/css/request/request.css">

<?php
$list = isset($pendingLeaves) && is_array($pendingLeaves) ? $pendingLeaves : [];
?>

<div class="box">
    <div class="container info-box-large">
        <div class="heading-section">
            <span class="heading-text">Pending Leave Requests</span>
            <span class="sub-heding-text">Review and approve leave requests</span>
        </div>

        <?php if (empty($list)): ?>
            <p style="padding:20px;">No pending leave requests.</p>
        <?php else: ?>

            <?php foreach ($list as $req): ?>
                <?php
                $requestedDate = !empty($req['createdAt']) ? date('M d Y', strtotime($req['createdAt'])) : '';
                $from = !empty($req['dateFrom']) ? date('M d Y', strtotime($req['dateFrom'])) : '';
                $to   = !empty($req['dateTo']) ? date('M d Y', strtotime($req['dateTo'])) : '';

                $remainLabel = '';
                if (!empty($req['dateFrom'])) {
                    $today = new DateTime();
                    $start = new DateTime($req['dateFrom']);
                    $diffDays = (int)$today->diff($start)->format('%r%a');

                    if ($diffDays > 0) {
                        $remainLabel = $diffDays . " days remaining";
                        $labelClass = "label label-green";
                    } elseif ($diffDays === 0) {
                        $remainLabel = "Starts today";
                        $labelClass = "label label-green";
                    } else {
                        $remainLabel = "Started";
                        $labelClass = "label label-red";
                    }
                } else {
                    $labelClass = "label label-green";
                }

                $usedLeaveDays = isset($req['used_leave_days']) ? (int)$req['used_leave_days'] : 0;
                $remainingLeaveDays = isset($req['remaining_leave_days']) ? (int)$req['remaining_leave_days'] : 25;
                ?>

                <div class="info-box border-container">
                    <div class="left">
                        <span class="heading-name">
                            <?= htmlspecialchars($req['teacher_name'] ?? 'Unknown') ?>
                            <span style="font-weight:500; color:#6b7280;">
                                (ID: <?= htmlspecialchars($req['teacher_id'] ?? $req['teacherUserID'] ?? '') ?>)
                            </span>
                        </span>

                        <span class="sub-heading">From: <?= htmlspecialchars($from) ?> → To: <?= htmlspecialchars($to) ?></span>

                        <span class="sub-heading-bolt"><?= ucfirst(htmlspecialchars($req['leaveType'] ?? '')) ?> Leave</span>

                        <span class="sub-heading">Submitted on <?= htmlspecialchars($requestedDate) ?></span>

                        <?php if (!empty($req['reason'])): ?>
                            <span class="sub-heading"><?= htmlspecialchars($req['reason']) ?></span>
                        <?php endif; ?>

                        <div style="margin-top:10px; padding:10px 14px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; width:fit-content;">
                            <div style="font-size:14px; color:#374151; margin-bottom:4px;">
                                Used leave days this year: <strong><?= $usedLeaveDays ?></strong>/25
                            </div>
                            <div style="font-size:14px; color:#059669;">
                                Remaining leave days: <strong><?= $remainingLeaveDays ?></strong>
                            </div>
                        </div>

                        <?php if (!empty($remainLabel)): ?>
                            <label class="<?= $labelClass ?>" style="margin-top:10px; display:inline-block;">
                                <?= htmlspecialchars($remainLabel) ?>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="right two-com">
                        <form action="/index.php?url=leave/decide" method="post" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?= (int)($req['id'] ?? 0) ?>">
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-green" type="submit"
                                onclick="return confirm('Approve this leave request?');">
                                Approve
                            </button>
                        </form>

                        <form action="/index.php?url=leave/decide" method="post" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?= (int)($req['id'] ?? 0) ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-red" type="submit"
                                onclick="return confirm('Reject this leave request?');">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>