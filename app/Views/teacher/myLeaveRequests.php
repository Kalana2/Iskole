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
        $sample = [
            [
                'type' => 'Medical Leave',
                'dateFrom' => '2025-01-15',
                'dateTo' => '2025-01-17',
                'reason' => 'Medical checkup and recovery period as advised by doctor.',
                'requestedDate' => '2025-01-10',
                'status' => 'approved',
                'duration' => 3,
            ],
            [
                'type' => 'Personal Leave',
                'dateFrom' => '2025-02-20',
                'dateTo' => '2025-02-21',
                'reason' => 'Family event requiring my presence.',
                'requestedDate' => '2025-02-15',
                'status' => 'pending',
                'duration' => 2,
            ],
            [
                'type' => 'Duty Leave',
                'dateFrom' => '2025-01-05',
                'dateTo' => '2025-01-05',
                'reason' => 'Attending educational workshop at the district office.',
                'requestedDate' => '2025-01-02',
                'status' => 'approved',
                'duration' => 1,
            ],
            [
                'type' => 'Medical Leave',
                'dateFrom' => '2024-12-10',
                'dateTo' => '2024-12-12',
                'reason' => 'Flu and fever, doctor recommended rest.',
                'requestedDate' => '2024-12-08',
                'status' => 'rejected',
                'duration' => 3,
            ],
        ];
        $list = isset($leaveRequests) && is_array($leaveRequests) ? $leaveRequests : $sample;
        ?>

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
            <article role="listitem" class="<?php echo implode(' ', $classes); ?>" tabindex="0"
                aria-label="Leave Request: <?php echo htmlspecialchars($req['type'] ?? ''); ?>">
                <div class="ann-card-header">
                    <div class="ann-badges">
                        <span class="<?php echo $statusColor; ?>"
                            aria-label="<?php echo $statusLabel; ?>"><?php echo $statusLabel; ?></span>
                        <span class="badge"><?php echo htmlspecialchars($req['type'] ?? ''); ?></span>
                    </div>
                    <time class="ann-date"
                        datetime="<?php echo htmlspecialchars($req['requestedDate'] ?? ''); ?>">Requested: <?php echo htmlspecialchars($req['requestedDate'] ?? ''); ?></time>
                </div>

                <h3 class="ann-title-text"><?php echo htmlspecialchars($req['type'] ?? ''); ?> - <?php echo htmlspecialchars($req['duration'] ?? ''); ?> Day(s)</h3>
                <p class="ann-body"><?php echo htmlspecialchars($req['reason'] ?? ''); ?></p>

                <div class="ann-meta">
                    <span class="author">From: <?php echo htmlspecialchars($req['dateFrom'] ?? ''); ?></span>
                    <span class="author">To: <?php echo htmlspecialchars($req['dateTo'] ?? ''); ?></span>
                </div>

                <div class="ann-actions-row">
                    <button class="btn ghost" type="button">View Details</button>
                    <div class="spacer"></div>
                    <?php if ($status === 'pending'): ?>
                        <button class="btn" type="button">Cancel Request</button>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
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