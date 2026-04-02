<?php
// filepath: d:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\parent\parentBehavior.php

$behaviorReports = $behaviorReports ?? [];
?>
<link rel="stylesheet" href="/css/parentBehavior/parentBehavior.css">

<section class="parent-behavior-section theme-light" aria-labelledby="behavior-title">
    <div class="box">
        <!-- Header Section -->
        <div class="heading-section">
            <h1 class="heading-text" id="behavior-title">Behavior Reports</h1>
            <p class="sub-heding-text">Teacher observations, behavioral notes, and academic conduct updates</p>
        </div>

        <!-- Filter Chips -->
        <div class="filter-wrapper">
            <div class="chip-group" role="tablist" aria-label="Behavior report filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">
                    All Reports
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="positive">
                    Positive
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="neutral">
                    Neutral
                </button>
                <button class="chip" role="tab" aria-selected="false" data-filter="concern">
                    Concerns
                </button>
            </div>
        </div>

        <!-- Behavior Reports List -->
        <div class="behavior-list">
            <?php if (!empty($behaviorReports)): ?>
                <?php foreach ($behaviorReports as $report): ?>
                    <?php
                    $reportDate = isset($report['report_date']) && $report['report_date'] !== ''
                        ? date('F j, Y', strtotime($report['report_date']))
                        : 'N/A';
                    $reportType = $report['report_type'] ?? 'neutral';
                    $teacherName = $report['teacher_name'] ?? 'Unknown Teacher';
                    $teacherSubject = $report['teacher_subject'] ?? '';
                    $category = $report['category'] ?? 'General';
                    ?>
                    <div class="behavior-report <?php echo htmlspecialchars($reportType); ?>" data-type="<?php echo htmlspecialchars($reportType); ?>">
                        <div class="report-header">
                            <div class="report-info">
                                <div class="teacher-details">
                                    <span class="reporter"><?php echo htmlspecialchars($teacherName); ?></span>
                                    <?php if ($teacherSubject): ?>
                                        <span class="subject-badge"><?php echo htmlspecialchars($teacherSubject); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="report-meta">
                                    <span class="repo-date"><?php echo htmlspecialchars($reportDate); ?></span>
                                    <span class="category-badge"><?php echo htmlspecialchars($category); ?></span>
                                </div>
                            </div>
                            <div class="report-type-indicator">
                                <span class="type-badge <?php echo htmlspecialchars($reportType); ?>">
                                    <?php
                                    $typeIcons = [
                                        'positive' => '✓',
                                        'neutral' => '◉',
                                        'concern' => '⚠'
                                    ];
                                    echo $typeIcons[$reportType] ?? '◉';
                                    ?>
                                    <?php echo ucfirst($reportType); ?>
                                </span>
                            </div>
                        </div>

                        <?php if (isset($report['title'])): ?>
                            <div class="report-title">
                                <?php echo htmlspecialchars($report['title']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="report-content">
                            <p><?php echo htmlspecialchars($report['description'] ?? 'No description provided'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h3>No Behavior Reports</h3>
                    <p>There are no behavior reports available at this time.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary Stats -->
        <div class="stats-section">
            <div class="stat-card positive">
                <div class="stat-icon">😊</div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo count(array_filter($behaviorReports, fn($r) => $r['report_type'] === 'positive')); ?>
                    </div>
                    <div class="stat-label">Positive Reports</div>
                </div>
            </div>
            <div class="stat-card neutral">
                <div class="stat-icon">😐</div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo count(array_filter($behaviorReports, fn($r) => $r['report_type'] === 'neutral')); ?>
                    </div>
                    <div class="stat-label">Neutral Reports</div>
                </div>
            </div>
            <div class="stat-card concern">
                <div class="stat-icon">⚠️</div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo count(array_filter($behaviorReports, fn($r) => $r['report_type'] === 'concern')); ?>
                    </div>
                    <div class="stat-label">Concerns</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const chips = document.querySelectorAll('.chip[data-filter]');
        const reports = document.querySelectorAll('.behavior-report[data-type]');

        chips.forEach(chip => {
            chip.addEventListener('click', function() {
                // Update active state
                chips.forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                // Filter reports
                const filter = this.dataset.filter;
                reports.forEach(report => {
                    if (filter === 'all' || report.dataset.type === filter) {
                        report.style.display = 'block';
                        // Add animation
                        report.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        report.style.display = 'none';
                    }
                });
            });
        });
    });
</script>