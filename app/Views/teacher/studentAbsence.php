<?php
// filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\studentAbsence.php

// Sample data for student absence requests
$absences = [
    [
        'id' => 1,
        'fName' => 'Amal',
        'lName' => 'Perera',
        'grade' => '10',
        'class' => 'A',
        'from_date' => '2025-11-10',
        'to_date' => '2025-11-12',
        'reason' => 'Medical appointment and recovery period. Will provide medical certificate upon return.',
        'submitted_date' => '2025-11-05',
        'status' => 'pending',
        'duration' => 3,
        'parent_contact' => '077-1234567'
    ],
    [
        'id' => 2,
        'fName' => 'Nimal',
        'lName' => 'Silva',
        'grade' => '10',
        'class' => 'B',
        'from_date' => '2025-11-08',
        'to_date' => '2025-11-08',
        'reason' => 'Family emergency requiring immediate attention.',
        'submitted_date' => '2025-11-07',
        'status' => 'pending',
        'duration' => 1,
        'parent_contact' => '071-9876543'
    ],
    [
        'id' => 3,
        'fName' => 'Kumari',
        'lName' => 'Fernando',
        'grade' => '11',
        'class' => 'A',
        'from_date' => '2025-11-06',
        'to_date' => '2025-11-07',
        'reason' => 'Attending a regional sports tournament representing the school.',
        'submitted_date' => '2025-11-01',
        'status' => 'approved',
        'duration' => 2,
        'parent_contact' => '075-5551234',
        'approved_by' => 'Mrs. Jayawardena',
        'approved_date' => '2025-11-02'
    ],
    [
        'id' => 4,
        'fName' => 'Saman',
        'lName' => 'Rajapaksa',
        'grade' => '9',
        'class' => 'C',
        'from_date' => '2025-11-15',
        'to_date' => '2025-11-17',
        'reason' => 'Cultural event participation as school representative.',
        'submitted_date' => '2025-11-04',
        'status' => 'pending',
        'duration' => 3,
        'parent_contact' => '078-3334567'
    ],
    [
        'id' => 5,
        'fName' => 'Dilini',
        'lName' => 'Wickramasinghe',
        'grade' => '10',
        'class' => 'A',
        'from_date' => '2025-11-01',
        'to_date' => '2025-11-01',
        'reason' => 'Dental treatment scheduled by orthodontist.',
        'submitted_date' => '2025-10-28',
        'status' => 'rejected',
        'duration' => 1,
        'parent_contact' => '076-7778899',
        'rejected_by' => 'Mr. Bandara',
        'rejected_date' => '2025-10-30',
        'rejection_reason' => 'Important exam scheduled on this date.'
    ]
];
?>
<link rel="stylesheet" href="/css/studentAbsence/studentAbsence.css">

<section class="student-absence-section theme-light" aria-labelledby="absence-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <h1 class="heading-text" id="absence-title">Student Absence Requests</h1>
            <p class="sub-heding-text">Review and manage student absence requests from your classes</p>
        </div>

        <!-- Filter Chips -->
        <div class="filter-wrapper">
            <div class="chip-group" role="tablist" aria-label="Absence request filters">
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

        <!-- Absence Request Cards -->
        <div class="container info-box-large">
            <?php if (!empty($absences)): ?>
                <?php foreach ($absences as $req): ?>
                    <?php
                    $fromTs = isset($req['from_date']) && $req['from_date'] !== '' ? strtotime($req['from_date']) : false;
                    $toTs = isset($req['to_date']) && $req['to_date'] !== '' ? strtotime($req['to_date']) : false;
                    $fromFmt = $fromTs ? date('F j, Y', $fromTs) : 'N/A';
                    $toFmt = $toTs ? date('F j, Y', $toTs) : 'N/A';
                    $studentName = trim(($req['fName'] ?? '') . ' ' . ($req['lName'] ?? ''));
                    $classStr = (isset($req['grade']) ? $req['grade'] : '-') . '-' . (isset($req['class']) ? $req['class'] : '-');
                    $status = $req['status'] ?? 'pending';
                    $duration = $req['duration'] ?? 1;
                    $submittedDate = isset($req['submitted_date']) && $req['submitted_date'] !== ''
                        ? date('M j, Y', strtotime($req['submitted_date']))
                        : 'N/A';
                    ?>
                    <div class="info-box border-container" data-status="<?php echo htmlspecialchars($status); ?>">
                        <div class="left">
                            <h3 class="heading-name">
                                <?php echo htmlspecialchars($studentName ?: 'Unknown Student'); ?>
                                <span class="student-class">(Class <?php echo htmlspecialchars($classStr); ?>)</span>
                            </h3>
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
                                <?php if (isset($req['parent_contact'])): ?>
                                    <span class="separator">•</span>
                                    <span>Contact: <?php echo htmlspecialchars($req['parent_contact']); ?></span>
                                <?php endif; ?>
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
                            <?php if ($status === 'pending'): ?>
                                <button class="btn btn-green" onclick="approveRequest(<?php echo $req['id']; ?>)">
                                    Approve
                                </button>
                                <button class="btn btn-red" onclick="rejectRequest(<?php echo $req['id']; ?>)">
                                    Reject
                                </button>
                            <?php else: ?>
                                <span class="label <?php echo $status === 'approved' ? 'label-green' : 'label-red'; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="info-box empty-state">
                    <div class="left">
                        <h3 class="heading-name">No Absence Requests</h3>
                        <p class="sub-heading">There are no student absence requests at the moment.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

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
    });

    // Action handlers (to be implemented with backend)
    function approveRequest(id) {
        if (confirm('Are you sure you want to approve this absence request?')) {
            console.log('Approving request:', id);
            // TODO: Implement AJAX call to backend
            alert('Approval functionality to be implemented');
        }
    }

    function rejectRequest(id) {
        const reason = prompt('Please provide a reason for rejection (optional):');
        if (reason !== null) {
            console.log('Rejecting request:', id, 'Reason:', reason);
            // TODO: Implement AJAX call to backend
            alert('Rejection functionality to be implemented');
        }
    }
</script>