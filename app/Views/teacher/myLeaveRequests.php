<?php
// filepath: /d:/Semester 4/SCS2202 - Group Project/Iskole/app/Views/teacher/myLeaveRequests.php
?>
<link rel="stylesheet" href="/css/announcements/announcements.css">

<section class="mp-announcements theme-light" aria-labelledby="leave-req-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="leave-req-title">My Leave Requests</h2>
            <p class="ann-subtitle">View and track your submitted leave requests</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Leave request filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">All</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="pending">Pending</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="approved">Approved</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="rejected">Rejected</button>
            </div>
        </div>
    </div>

    <div class="ann-grid" role="list">




        <?php
        // Expecting: $leaveRequests = [ [ 'type' => '', 'dateFrom' => '', 'dateTo' => '', 'reason' => '', 'requestedDate' => '', 'status' => 'pending|approved|rejected' ], ... ]
        $now = date('Y-m-d');

        $list = isset($leaveRequests) && is_array($leaveRequests) ? $leaveRequests : [];

        ?>

        <?php if (empty($list)): ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Leave Requests</h3>
                <p>You haven’t submitted any leave requests yet.</p>
            </div>
        <?php else: ?>





        <?php foreach ($list as $i => $req): ?>
            <?php
            $status = strtolower($req['status'] ?? 'pending');
            $classes = ['ann-card', 'status-' . $status];
            $statusLabel = ucfirst($status);
            $statusColor = [
                'pending' => 'badge',
                'approved' => 'badge badge-pin',
                'rejected' => 'badge',
            ][$status] ?? 'badge';
            ?>

            <?php
            $type = ucfirst($req['leaveType'] ?? '');

            $requestedDate = !empty($req['createdAt'])
                ? date('Y-m-d', strtotime($req['createdAt']))
                : '';

            $duration = 1;
            if (!empty($req['dateFrom']) && !empty($req['dateTo'])) {
                $from = new DateTime($req['dateFrom']);
                $to   = new DateTime($req['dateTo']);
                $duration = $from->diff($to)->days + 1;
            }
            ?>

            <article role="listitem" class="<?php echo implode(' ', $classes); ?>" tabindex="0"
                aria-label="Leave Request: <?php echo htmlspecialchars($type ?? ''); ?>">
                <div class="ann-card-header">
                    <div class="ann-badges">
                        <span class="<?php echo $statusColor; ?>"
                            aria-label="<?php echo $statusLabel; ?>"><?php echo $statusLabel; ?></span>
                        <span class="badge"><?php echo htmlspecialchars($type ?? ''); ?></span>
                    </div>
                    <time class="ann-date"
                        datetime="<?php echo htmlspecialchars($requestedDate ?? ''); ?>">Requested: <?php echo htmlspecialchars($requestedDate ?? ''); ?></time>
                </div>

                <h3 class="ann-title-text"><?php echo htmlspecialchars($type ?? ''); ?> - <?php echo htmlspecialchars($duration ?? ''); ?> Day(s)</h3>
                <p class="ann-body"><?php echo htmlspecialchars($req['reason'] ?? ''); ?></p>

                <div class="ann-meta">
                    <span class="author">From: <?php echo htmlspecialchars($req['dateFrom'] ?? ''); ?></span>
                    <span class="author">To: <?php echo htmlspecialchars($req['dateTo'] ?? ''); ?></span>
                </div>

                <div class="ann-actions-row">
                    <button class="btn ghost" type="button">View Details</button>
                    <div class="spacer"></div>
                    <?php if ($status === 'pending'): ?>
                        <form action="/index.php?url=leave/cancel" method="post" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?= (int)$req['id'] ?>">
                            <button class="btn" type="submit"
                                onclick="return confirm('Cancel this leave request?')">
                                Cancel Request
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </article>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script>
    (function() {
        const container = document.currentScript.previousElementSibling; // section.mp-announcements
        if (!container) return;
        const grid = container.querySelector('.ann-grid');
        const chips = container.querySelectorAll('.chip-group .chip');

        const applyFilter = (key) => {
            const cards = grid.querySelectorAll('.ann-card');
            cards.forEach(card => {
                let show = true;
                switch (key) {
                    case 'all':
                        show = true;
                        break;
                    case 'pending':
                        show = card.classList.contains('status-pending');
                        break;
                    case 'approved':
                        show = card.classList.contains('status-approved');
                        break;
                    case 'rejected':
                        show = card.classList.contains('status-rejected');
                        break;
                }
                card.style.display = show ? '' : 'none';
            });
        };

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-selected', 'false');
                });
                chip.classList.add('active');
                chip.setAttribute('aria-selected', 'true');
                applyFilter(chip.dataset.filter);
            });
        });
    })();
</script>