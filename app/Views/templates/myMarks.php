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
        <h2 id="mymarks-title"><?= $isParent ? "Child's Marks & Performance Report" : "My Marks & Performance Report" ?>
        </h2>
        <p class="subtitle">
          <?= $isParent ? "Your child's marks and progress across all subjects" : "marks and progress across all subjects" ?>
        </p>
      </div>
      <!-- Child info badge - shown for parents -->
      <div class="student-info-badge" id="childInfoBadge" style="display: <?= $isParent ? 'flex' : 'none' ?>;">
        <div class="info-item">
          <span class="info-label">Student name</span>
          <span class="info-value" id="childName">—</span>
        </div>
        <div class="info-item">
          <span class="info-label">Class</span>
          <span class="info-value" id="childClass">—</span>
        </div>
        <div class="info-item">
          <span class="info-label">Academic Year</span>
          <span class="info-value" id="childAcademicYear">—</span>
        </div>
      </div>
    </header>

    <div class="center-container card">

      <!-- Subject Marks Card -->
      <div class="marks-card">
        <div class="marks-card-header">
          <h3 class="marks-card-title">Subject Marks</h3>
          <div class="term-pill-switcher" id="termSwitcher">
            <button class="term-pill" data-term="term1">Term 1</button>
            <button class="term-pill" data-term="term2">Term 2</button>
            <button class="term-pill" data-term="term3">Term 3</button>
            <button class="term-pill active" data-term="all">All terms</button>
          </div>
        </div>
        <div class="marks-table-wrapper">
          <table class="marks-table" id="marksTable">
            <thead>
              <tr>
                <th>Subject</th>
                <th data-term="term1">Term 1</th>
                <th data-term="term2">Term 2</th>
                <th data-term="term3">Term 3</th>
                <th>Average</th>
                <th>Grade</th>
              </tr>
            </thead>
            <tbody id="marksTableBody">
              <!-- Populated by JS -->
            </tbody>
            <tfoot id="marksTableFoot">
              <!-- Populated by JS -->
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Performance Chart (below table) -->
      <div class="performance-report">
        <h3 class="report-title">Performance Trend</h3>

        <div class="chart-controls"></div>

        <div class="chart-container">
          <canvas id="performanceChart"></canvas>
        </div>
      </div>

    </div>
  </div>
</section>