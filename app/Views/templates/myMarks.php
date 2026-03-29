<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/myMarks.php
$userRole = $this->session->get('userRole') ?? $this->session->get('user_role') ?? 3;
$isParent = ($userRole == 4);
?>
<section class="reports-entry tab-panel mp-management">
  <input type="hidden" id="studentId" value="<?= $this->session->get('user_id') ?>">
  <input type="hidden" id="isParentView" value="<?= $isParent ? '1' : '0' ?>">
  <div class="reports-section">
    <header class="mgmt-header">
      <div class="title-wrap">
        <h2 id="mymarks-title"><?= $isParent ? "Child's Marks & Performance Report" : "My Marks & Performance Report" ?></h2>
        <p class="subtitle"><?= $isParent ? "Your child's marks and progress across all subjects" : "marks and progress across all subjects" ?></p>
      </div>
      <!-- Child info badge - shown for parents -->
      <div class="student-info-badge" id="childInfoBadge" style="display: <?= $isParent ? 'flex' : 'none' ?>;">
        <div class="info-item">
          <span class="info-label">Student name</span>
          <span class="info-value" id="childName">—</span>
        </div>
      </div>
    </header>

    <div class="center-container card">
      <!-- Stats Overview -->
      <div class="stats-overview">
        <div class="stat-card">
          <div class="stat-icon">🏆</div>
          <div class="stat-content">
            <h4>Section Rank</h4>
            <p class="stat-value">#5</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-content">
            <h4>Class Rank</h4>
            <p class="stat-value">#1</p>
          </div>
        </div>
      </div>

      <!-- Performance Report with Charts -->
      <div class="performance-report">
        <h3 class="report-title">
          <span>Performance Report</span>
        </h3>

        <div class="chart-controls"></div>

        <div class="chart-container">
          <canvas id="performanceChart"></canvas>
        </div>

        <!-- Detailed Marks removed -->
      </div>

      <!-- Recent Exam Results removed -->
    </div>
  </div>
</section>