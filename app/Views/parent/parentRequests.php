<?php
// filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\parent\parentrequests.php

// Sample data for parent absence requests
$recentRequests = [
    [
        'id' => 1,
        'request_id' => 1,
        'from_date' => '2025-11-10',
        'to_date' => '2025-11-12',
        'reason' => 'Medical appointment and recovery period. Will provide medical certificate upon return.',
        'submitted_date' => '2025-11-05',
        'status' => 'pending',
        'duration' => 3
    ],
    [
        'id' => 2,
        'request_id' => 2,
        'from_date' => '2025-11-08',
        'to_date' => '2025-11-08',
        'reason' => 'Family emergency requiring immediate attention.',
        'submitted_date' => '2025-11-07',
        'status' => 'approved',
        'duration' => 1,
        'approved_by' => 'Mrs. Jayawardena',
        'approved_date' => '2025-11-07'
    ],
    [
        'id' => 3,
        'request_id' => 3,
        'from_date' => '2025-11-15',
        'to_date' => '2025-11-17',
        'reason' => 'Attending a cultural event as school representative.',
        'submitted_date' => '2025-11-04',
        'status' => 'pending',
        'duration' => 3
    ],
    [
        'id' => 4,
        'request_id' => 4,
        'from_date' => '2025-11-01',
        'to_date' => '2025-11-01',
        'reason' => 'Dental treatment scheduled by orthodontist.',
        'submitted_date' => '2025-10-28',
        'status' => 'rejected',
        'duration' => 1,
        'rejected_by' => 'Mr. Bandara',
        'rejected_date' => '2025-10-30',
        'rejection_reason' => 'Important exam scheduled on this date.'
    ],
    [
        'id' => 5,
        'request_id' => 5,
        'from_date' => '2025-10-28',
        'to_date' => '2025-10-30',
        'reason' => 'Attending regional sports tournament.',
        'submitted_date' => '2025-10-20',
        'status' => 'approved',
        'duration' => 3,
        'approved_by' => 'Mrs. Silva',
        'approved_date' => '2025-10-22'
    ]
];
?>
<link rel="stylesheet" href="/css/parentRequests/parentRequests.css">

<section class="parent-requests-section theme-light" aria-labelledby="requests-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <h1 class="heading-text" id="requests-title">Absence Request Status</h1>
            <p class="sub-heding-text">Track your submitted absence requests and their approval status</p>
        </div>

        <!-- Filter Chips -->
        <div class="filter-wrapper">
            <div class="chip-group" role="tablist" aria-label="Request status filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">
                    All Requests
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="pending">
                    Pending
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="approved">
                    Approved
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="rejected">
                    Rejected
                </button>
            </div>
        </div>

        <!-- Request Cards -->
        <div class="container info-box-large">
            <?php if (!empty($recentRequests)): ?>
                <?php foreach ($recentRequests as $req): ?>
                    <?php
                    $fromTs = isset($req['from_date']) && $req['from_date'] !== '' ? strtotime($req['from_date']) : false;
                    $toTs = isset($req['to_date']) && $req['to_date'] !== '' ? strtotime($req['to_date']) : false;
                    $fromFmt = $fromTs ? date('F j, Y', $fromTs) : 'N/A';
                    $toFmt = $toTs ? date('F j, Y', $toTs) : 'N/A';
                    $status = $req['status'] ?? 'pending';
                    $duration = $req['duration'] ?? 1;
                    $submittedDate = isset($req['submitted_date']) && $req['submitted_date'] !== ''
                        ? date('M j, Y', strtotime($req['submitted_date']))
                        : 'N/A';
                    $requestId = isset($req['request_id']) ? (int) $req['request_id'] : (isset($req['id']) ? (int) $req['id'] : 0);
                    $fromInput = $fromTs ? date('Y-m-d', $fromTs) : '';
                    $toInput = $toTs ? date('Y-m-d', $toTs) : '';
                    ?>
                    <div class="info-box border-container" data-status="<?php echo htmlspecialchars($status); ?>">
                        <div class="left">
                            <p class="sub-heading-bolt">
                                <?php echo htmlspecialchars($fromFmt); ?>
                                <?php if ($fromFmt !== $toFmt): ?>
                                    - <?php echo htmlspecialchars($toFmt); ?>
                                <?php endif; ?>
                                <span class="duration-badge"><?php echo $duration; ?> day<?php echo $duration > 1 ? 's' : ''; ?></span>
                            </p>
                            <p class="sub-heading"><?php echo htmlspecialchars($req['reason'] ?? 'No reason provided'); ?></p>
                            <p class="sub-heading meta-info">
                                <span>Submitted: <?php echo htmlspecialchars($submittedDate); ?></span>
                            </p>

                            <?php if ($status === 'approved' && isset($req['approved_by'])): ?>
                                <p class="status-note approved">
                                    ✓ Approved by <?php echo htmlspecialchars($req['approved_by']); ?>
                                    on <?php echo date('M j, Y', strtotime($req['approved_date'])); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($status === 'rejected' && isset($req['rejected_by'])): ?>
                                <p class="status-note rejected">
                                    ✗ Rejected by <?php echo htmlspecialchars($req['rejected_by']); ?>
                                    on <?php echo date('M j, Y', strtotime($req['rejected_date'])); ?>
                                    <?php if (isset($req['rejection_reason'])): ?>
                                        <br><small>Reason: <?php echo htmlspecialchars($req['rejection_reason']); ?></small>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="right two-com">
                            <span class="label <?php echo $status === 'approved' ? 'label-green' : ($status === 'rejected' ? 'label-red' : 'label-warning'); ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                            <?php if ($status === 'pending'): ?>
                                <button type="button" class="btn btn-primary btn-edit-leave"
                                    data-id="<?php echo $requestId; ?>"
                                    data-from="<?php echo htmlspecialchars($fromInput); ?>"
                                    data-to="<?php echo htmlspecialchars($toInput); ?>"
                                    data-reason="<?php echo htmlspecialchars($req['reason'] ?? '', ENT_QUOTES); ?>">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-red" onclick="deleteRequest(<?php echo $requestId; ?>)">
                                    Delete
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="info-box empty-state">
                    <div class="left">
                        <h3 class="heading-name">No Absence Requests</h3>
                        <p class="sub-heading">You haven't submitted any absence requests yet.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Submit New Request Form -->
        <div class="form-section">
            <div class="heading-section">
                <h2 class="heading-text">Submit New Absence Request</h2>
                <p class="sub-heding-text">Request absence in advance with proper reason</p>
            </div>

            <form class="leave-request-form" action="../../Controllers/leaveReqController.php" method="POST">
                <div class="date-row">
                    <div class="form-group">
                        <label for="from-date">From Date</label>
                        <input type="date" id="from-date" name="fromDate" class="input-date" required />
                    </div>
                    <div class="form-group">
                        <label for="to-date">To Date</label>
                        <input type="date" id="to-date" name="toDate" class="input-date" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="reason">Reason for Absence</label>
                    <textarea id="reason" name="reason" class="textarea-details"
                        placeholder="Please provide detailed reason for the absence request"
                        rows="4" required></textarea>
                </div>

                <button type="submit" class="btn-submit">Submit Request</button>
            </form>
        </div>
    </div>
</section>

<!-- Edit Leave Modal -->
<div id="editLeaveModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Edit Absence Request</h3>
            <button class="modal-close" type="button" aria-label="Close">×</button>
        </div>
        <form id="editLeaveForm" method="POST" action="../../Controllers/leaveReqController.php">
            <input type="hidden" name="edit_request_id" id="edit-request-id" value="">
            <div class="modal-body">
                <div class="date-row">
                    <div class="form-group">
                        <label for="edit-from-date">From Date</label>
                        <input type="date" id="edit-from-date" name="fromDate" class="input-date" required />
                    </div>
                    <div class="form-group">
                        <label for="edit-to-date">To Date</label>
                        <input type="date" id="edit-to-date" name="toDate" class="input-date" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-reason">Reason for Absence</label>
                    <textarea id="edit-reason" name="reason" class="textarea-details"
                        placeholder="Update absence reason" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const chips = document.querySelectorAll('.chip[data-filter]');
        const cards = document.querySelectorAll('.info-box[data-status]');

        chips.forEach(chip => {
            chip.addEventListener('click', function() {
                // Update active state
                chips.forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                // Filter cards
                const filter = this.dataset.filter;
                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Modal functionality
        const modal = document.getElementById('editLeaveModal');
        const editButtons = document.querySelectorAll('.btn-edit-leave');
        const closeButtons = document.querySelectorAll('.modal-close, .modal-cancel');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const fromDate = this.dataset.from;
                const toDate = this.dataset.to;
                const reason = this.dataset.reason;

                document.getElementById('edit-request-id').value = id;
                document.getElementById('edit-from-date').value = fromDate;
                document.getElementById('edit-to-date').value = toDate;
                document.getElementById('edit-reason').value = reason;

                modal.style.display = 'flex';
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });

        // Close modal on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    // Delete functionality
    function deleteRequest(id) {
        if (confirm('Are you sure you want to delete this absence request?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../Controllers/leaveReqController.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_request_id';
            input.value = id;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>