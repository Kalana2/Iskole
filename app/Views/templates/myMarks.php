<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/myMarks.php
?>
<section class="reports-entry tab-panel mp-management">
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
          <div class="chart-toggle">
            <button class="toggle-btn active" data-chart="line">Trend</button>
            <button class="toggle-btn" data-chart="radar">Radar Chart</button>
          </div>
        </h3>

        <div class="chart-container">
          <canvas id="performanceChart"></canvas>
        </div>

        <!-- Detailed Marks removed -->
      </div>

      <!-- Recent Exam Results removed -->
    </div>
  </div>
</section>
