<?php
require_once __DIR__ . "/../../Controllers/StudentAbsenceReasonController.php";
$studentAbsence = new StudentAbsenceReasonController();
$absenceRequests = $studentAbsence->viewAbsencesByTeacherUserId($_SESSION['user_id']);
// Use the data fetched from the database
$absences = $absenceRequests;
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
                <button class="chip" role="tab" aria-selected="false" data-filter="acknowledged">
                    Acknowledgements
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
                                <span class="duration-badge"><?php echo $duration; ?>
                                    day<?php echo $duration > 1 ? 's' : ''; ?></span>
                            </p>
                            <p class="sub-heading"><?php echo htmlspecialchars($req['reason'] ?? 'No reason provided'); ?></p>
                            <p class="sub-heading meta-info">
                                <span>Submitted: <?php echo htmlspecialchars($submittedDate); ?></span>
                                <?php if (isset($req['parent_contact'])): ?>
                                    <span class="separator">•</span>
                                    <span>Contact: <?php echo htmlspecialchars($req['parent_contact']); ?></span>
                                <?php endif; ?>
                            </p>

                            <?php if ($status === 'acknowledged' && isset($req['acknowledged_by'])): ?>
                                <p class="status-note acknowledged">
                                    ✓ Acknowledged by <?php echo htmlspecialchars($req['acknowledged_by']); ?>
                                    on <?php echo date('M j, Y', strtotime($req['acknowledged_date'])); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="right two-com">
                            <?php if ($status === 'pending'): ?>
                                <button class="btn btn-green" onclick="acknowledgeRequest(<?php echo $req['id']; ?>)">
                                    Acknowledge
                                </button>
                            <?php else: ?>
                                <span class="label label-green">
                                    Acknowledged
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
    });

    // Action handlers
    function acknowledgeRequest(id) {
        if (confirm('Are you sure you want to acknowledge this absence request?')) {
            // Disable the button to prevent double-clicks
            const button = event.target;
            button.disabled = true;
            button.textContent = 'Processing...';

            fetch('/studentAbsenceReason/acknowledge', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reasonId: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Absence request acknowledged successfully!');
                        // Reload the page to show updated status
                        location.reload();
                    } else {
                        alert('Failed to acknowledge: ' + (data.message || 'Unknown error'));
                        button.disabled = false;
                        button.textContent = 'Acknowledge';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while acknowledging the request.');
                    button.disabled = false;
                    button.textContent = 'Acknowledge';
                });
        }
    }
</script>