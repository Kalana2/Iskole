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
    </header>

    <div class="center-container card">
      <!-- Stats Overview -->
      <div class="stats-overview">
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-content">
            <h4>Overall Average</h4>
            <p class="stat-value">72.3%</p>
          </div>
        </div>
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

        <div class="chart-controls">
          <div class="term-selector">
            <div class="term-buttons" role="group" aria-label="Select term">
              <button type="button" class="term-btn" data-term="term1">Term 1</button>
              <button type="button" class="term-btn" data-term="term2">Term 2</button>
              <button type="button" class="term-btn active" data-term="term3">Term 3</button>
            </div>
          </div>
        </div>

        <div class="chart-container">
          <canvas id="performanceChart"></canvas>
        </div>

        <!-- Detailed Marks removed -->
      </div>

      <!-- Recent Exam Results removed -->
    </div>
  </div>
</section>
