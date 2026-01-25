<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/myMarks.php
?>
<section class="reports-entry tab-panel mp-management">
  <input type="hidden" id="studentId" value="<?= $this->session->get('user_id') ?>">
  <div class="reports-section">
    <header class="mgmt-header">
      <div class="title-wrap">
        <h2 id="mymarks-title">My Marks</h2>
        <p class="subtitle">Your marks and progress across all subjects</p>
      </div>
      <div class="student-info-badge">
        <div class="info-item">
            <span class="info-label">Student</span>
            <span class="info-value"><?php echo htmlspecialchars($studentInfo['name'] ?? '—'); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Class</span>
            <span class="info-value"><?php echo htmlspecialchars($studentInfo['class'] ?? '—'); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">ID</span>
            <span class="info-value"><?php echo htmlspecialchars($studentInfo['stu_id'] ?? '—'); ?></span>
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
