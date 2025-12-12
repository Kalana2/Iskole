<?php

require_once __DIR__ . "/../../Controllers/StudentAbsenceReasonController.php";
$studentAbsence = new StudentAbsenceReasonController();
$absenceRequests = $studentAbsence->viewAbsencesByParentUserId($_SESSION['user_id']);

// echo "<pre>";
// var_dump("Debugging absence requests:");
// var_dump($absenceRequests);
// echo "</pre>";

$recentRequests = $absenceRequests ?? [
    [
        'absenceID' => 1,
        'fromDate' => '2025-11-10',
        'toDate' => '2025-11-12',
        'reason' => 'Medical appointment and recovery period. Will provide medical certificate upon return.',
        'submittedDate' => '2025-11-05',
        'Status' => 'pending',
        'duration' => 3
    ],
    [
        'absenceID' => 2,
        'fromDate' => '2025-11-08',
        'toDate' => '2025-11-08',
        'reason' => 'Family emergency requiring immediate attention.',
        'submittedDate' => '2025-11-07',
        'Status' => 'acknowledged',
        'duration' => 1,
        'acknowledgedBy' => 'Mrs. Jayawardena',
        'acknowledgedDate' => '2025-11-07'
    ],
    [
        'absenceID' => 3,
        'fromDate' => '2025-11-15',
        'toDate' => '2025-11-17',
        'reason' => 'Attending a cultural event as school representative.',
        'submittedDate' => '2025-11-04',
        'Status' => 'pending',
        'duration' => 3
    ],
    [
        'absenceID' => 4,
        'fromDate' => '2025-11-01',
        'toDate' => '2025-11-01',
        'reason' => 'Dental treatment scheduled by orthodontist.',
        'submittedDate' => '2025-10-28',
        'Status' => 'not_seen',
        'duration' => 1
    ],
    [
        'absenceID' => 5,
        'fromDate' => '2025-10-28',
        'toDate' => '2025-10-30',
        'reason' => 'Attending regional sports tournament.',
        'submittedDate' => '2025-10-20',
        'Status' => 'acknowledged',
        'duration' => 3,
        'acknowledgedBy' => 'Mrs. Silva',
        'acknowledgedDate' => '2025-10-22'
    ]
];
?>
<link rel="stylesheet" href="/css/parentRequests/parentRequests.css">

<section class="parent-requests-section theme-light" aria-labelledby="requests-title">
    <div class="box">
        <?php if (isset($_SESSION['mgmt_msg'])): ?>
            <div class="alert alert-info"
                style="padding: 15px; margin-bottom: 20px; background: #e8f4f8; border-left: 4px solid #2196F3; border-radius: 4px;">
                <?php
                echo htmlspecialchars($_SESSION['mgmt_msg']);
                unset($_SESSION['mgmt_msg']);
                ?>
            </div>
        <?php endif; ?>

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
                <button class="chip" role="tab" aria-selected="false" data-filter="acknowledged">
                    Acknowledgements
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="not_seen">
                    Not Seen
                </button>
            </div>
        </div>

        <!-- Request Cards -->
        <div class="container info-box-large">
            <?php if (!empty($recentRequests)): ?>
                <?php foreach ($recentRequests as $req): ?>
                    <?php
                    $fromTs = isset($req['fromDate']) && $req['fromDate'] !== '' ? strtotime($req['fromDate']) : false;
                    $toTs = isset($req['toDate']) && $req['toDate'] !== '' ? strtotime($req['toDate']) : false;
                    $fromFmt = $fromTs ? date('F j, Y', $fromTs) : 'N/A';
                    $toFmt = $toTs ? date('F j, Y', $toTs) : 'N/A';
                    $status = $req['Status'] ?? 'pending';
                    $duration = $req['duration'] ?? 1;
                    $submittedDate = isset($req['submittedDate']) && $req['submittedDate'] !== ''
                        ? date('M j, Y', strtotime($req['submittedDate']))
                        : 'N/A';
                    $requestId = isset($req['reasonID']) ? (int) $req['reasonID'] : (isset($req['absenceID']) ? (int) $req['absenceID'] : 0);
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
                                <span class="duration-badge"><?php echo $duration; ?>
                                    day<?php echo $duration > 1 ? 's' : ''; ?></span>
                            </p>
                            <p class="sub-heading"><?php echo htmlspecialchars($req['reason'] ?? 'No reason provided'); ?></p>
                            <p class="sub-heading meta-info">
                                <span>Submitted: <?php echo htmlspecialchars($submittedDate); ?></span>
                            </p>

                            <?php if ($status === 'acknowledged' && isset($req['acknowledgedBy'])): ?>
                                <p class="status-note acknowledged">
                                    ✓ Acknowledged by <?php echo htmlspecialchars($req['acknowledgedBy']); ?>
                                    on <?php echo date('M j, Y', strtotime($req['acknowledgedDate'])); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($status === 'not_seen'): ?>
                                <p class="status-note not-seen">
                                    ⊘ Not yet reviewed by teacher
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="right two-com">
                            <span
                                class="label <?php echo $status === 'acknowledged' ? 'label-green' : ($status === 'not_seen' ? 'label-red' : 'label-warning'); ?>">
                                <?php echo $status === 'acknowledged' ? 'Acknowledged' : ($status === 'not_seen' ? 'Not Seen' : ucfirst($status)); ?>
                            </span>
                            <?php if ($status === 'pending' || $status === 'not_seen'): ?>
                                <button type="button" class="btn btn-primary btn-edit-leave" data-id="<?php echo $requestId; ?>"
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

            <form class="leave-request-form" action="/studentAbsenceReason/submit" method="POST">
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
                        placeholder="Please provide detailed reason for the absence request" rows="4"
                        required></textarea>
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
        <form id="editLeaveForm" method="POST" action="/studentAbsenceReason/edit">
            <input type="hidden" name="reasonId" id="edit-request-id" value="">
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
    document.addEventListener('DOMContentLoaded', function () {
        const chips = document.querySelectorAll('.chip[data-filter]');
        const cards = document.querySelectorAll('.info-box[data-status]');

        chips.forEach(chip => {
            chip.addEventListener('click', function () {
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
            button.addEventListener('click', function () {
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
            button.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });

        // Close modal on backdrop click
        modal.addEventListener('click', function (e) {
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
            form.action = '/studentAbsenceReason/delete';

            const reasonIdInput = document.createElement('input');
            reasonIdInput.type = 'hidden';
            reasonIdInput.name = 'reasonId';
            reasonIdInput.value = id;

            form.appendChild(reasonIdInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>