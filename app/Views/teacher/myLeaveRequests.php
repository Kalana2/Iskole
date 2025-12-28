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
                        <button class="btn ghost view-details-btn"
                            type="button"
                            data-reason="<?= htmlspecialchars($req['reason'] ?? '') ?>"
                            data-type="<?= htmlspecialchars($type ?? '') ?>"
                            data-from="<?= htmlspecialchars($req['dateFrom'] ?? '') ?>"
                            data-to="<?= htmlspecialchars($req['dateTo'] ?? '') ?>">
                            View Details
                        </button>

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


<div id="leaveModal" class="modal hidden">
    <div class="modal-content">
        <h3 id="modalTitle"></h3>
        <p id="modalDates"></p>
        <hr>
        <p id="modalReason"></p>

        <button class="btn" id="closeModal">Close</button>
    </div>
</div>

<script>
(function () {
    // ✅ Leave page section
    const section = document.querySelector('section.mp-announcements');
    if (!section) return;

    // =========================
    // ✅ FILTER (All / Pending / Approved / Rejected)
    // =========================
    const grid  = section.querySelector('.ann-grid');
    const chips = section.querySelectorAll('.chip-group .chip');

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

    // =========================
    // ✅ MODAL (View Details)
    // =========================
    const modal = document.getElementById('leaveModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDates = document.getElementById('modalDates');
    const modalReason = document.getElementById('modalReason');
    const closeBtn = document.getElementById('closeModal');

    // Safety check
    if (!modal || !modalTitle || !modalDates || !modalReason || !closeBtn) return;

    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = (btn.dataset.type || '') + ' Leave';
            modalDates.textContent = `From ${btn.dataset.from || ''} To ${btn.dataset.to || ''}`;
            modalReason.textContent = btn.dataset.reason || '';

            modal.classList.remove('hidden');
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // ✅ click outside modal-content -> close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // ✅ ESC key -> close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.classList.add('hidden');
        }
    });

})();
</script>
