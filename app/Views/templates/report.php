<!-- filepath: /home/snake/Projects/Iskole/app/Views/templates/report.php -->
<?php
// Controller එකෙන් එන data
$behaviorReports = $behaviorReports ?? [];
$flash = $flash ?? null;
$student = $student ?? null;
?>

<?php if ($flash): ?>
  <div style="margin:10px 0;padding:12px;border-radius:6px;
              border:1px solid <?= $flash['type'] === 'error' ? 'red' : 'green' ?>;
              color:<?= $flash['type'] === 'error' ? 'red' : 'green' ?>;">
    <strong><?= $flash['type'] === 'error' ? '❌ Error:' : '✅ Success:' ?></strong>
    <?= htmlspecialchars($flash['text']) ?>
  </div>
<?php endif; ?>




<section class="reports-entry tab-panel mp-management">
  <div class="reports-section">
    <header class="mgmt-header">
      <div class="title-wrap">
        <h2 id="report-title">Student Reports</h2>
        <p class="subtitle">View student progress reports & Behavior reports</p>
      </div>
    </header>

    <div class="center-container card">


      <div class="search-container">
        <form method="GET" action="/index.php" style="display: contents;">
          <input type="hidden" name="url" value="teacher">
          <input type="hidden" name="tab" value="Reports">

          <input
            type="text"
            placeholder="Search student..."
            id="searchInput"
            name="q"
            list="studentList"
            autocomplete="off"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            
          <datalist id="studentList">
            <?php foreach (($suggestions ?? []) as $s): ?>
              <option value="<?= htmlspecialchars(trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? ''))) ?>">
                <?= htmlspecialchars(trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? '')) . ' (' . $s['studentID'] . ')') ?>
              </option>
            <?php endforeach; ?>
          </datalist>


          <button type="submit" class="search-btn">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM18 18l-4.35-4.35"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Search
          </button>
        </form>
      </div>

      <div class="student-container">
        <!-- Student Details Card -->
        <?php if (!empty($student)): ?>
          <div class="student-info-card">
            <div class="student-avatar">
              <div class="avatar-circle">
                <?php
                $fn = $student['firstName'] ?? '';
                $ln = $student['lastName'] ?? '';
                $initials = strtoupper(mb_substr($fn, 0, 1) . mb_substr($ln, 0, 1));
                if ($initials === '') $initials = 'S';
                ?>
                <span><?= htmlspecialchars($initials) ?></span>
              </div>
            </div>

            <div class="details">
              <!-- ✅ REPLACE your <h2> with this line -->
              <h2 class="student-name">
                <?= htmlspecialchars(trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? '')) ?: 'N/A') ?>
              </h2>

              <div class="info-grid">
                <div class="info-item"><span class="label">Grade:</span>
                  <span class="value"><?= htmlspecialchars($student['gradeID'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">Class:</span>
                  <span class="value"><?= htmlspecialchars($student['classID'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">Student ID:</span>
                  <span class="value"><?= htmlspecialchars($student['studentID'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">Email:</span>
                  <span class="value"><?= htmlspecialchars($student['email'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">Phone:</span>
                  <span class="value"><?= htmlspecialchars($student['phone'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">DOB:</span>
                  <span class="value"><?= htmlspecialchars($student['dateOfBirth'] ?? 'N/A') ?></span>
                </div>
              </div>
            </div>
          </div>

        <?php elseif (!empty($_GET['q'])): ?>
          <div class="empty-state">
            <div class="empty-icon">🔎</div>
            <h3>No Student Found</h3>
            <p>This student is not in your class.</p>
          </div>
        <?php endif; ?>

        <!-- Performance Overview Cards -->
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
        </div>

        <!-- Behavior Section -->
        <div class="behavior-section">
          <div class="behavior-form-wrapper">
            <h3 class="report-title">Add Behavior Report</h3>
            <div class="behavior-update">
              <form action="/index.php?url=report/submit" method="POST">


                <?php if (!empty($student)): ?>
                  <input type="hidden" name="studentID" value="<?= htmlspecialchars($student['studentID']) ?>">
                <?php endif; ?>

                <div class="form-row">
                  <label for="report_type">Report Type</label>
                  <select id="report_type" name="report_type" required id="behaviorForm">
                    <option value="positive" selected>Positive</option>
                    <option value="neutral">Neutral</option>
                    <option value="concern">Concern</option>
                  </select>
                </div>
                <div class="form-row">
                  <label for="category">Category</label>
                  <input type="text" id="category" name="category" placeholder="e.g. Academic Excellence" required />
                </div>
                <div class="form-row">
                  <label for="title">Title</label>
                  <input type="text" id="title" name="title" placeholder="e.g. Excellent Leadership in Group Work" required />
                </div>
                <div class="form-row">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" rows="4" placeholder="Enter detailed observation..." required></textarea>
                </div>
                <button type="submit" class="update-behavior-btn">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                  </svg>
                  Add Report
                </button>
              </form>
            </div>
          </div>

          <div class="behavior-report-list">
            <h3 class="report-title">Recent Behavior Reports</h3>
            <?php if (!empty($behaviorReports)): ?>
              <?php foreach ($behaviorReports as $report): ?>
                <?php
                $reportDate = isset($report['report_date']) && $report['report_date'] !== '' ? date('F j, Y', strtotime($report['report_date'])) : 'N/A';
                $reportType = $report['report_type'] ?? 'neutral';
                $teacherName = $report['teacher_name'] ?? 'Unknown Teacher';
                $teacherSubject = $report['teacher_subject'] ?? '';
                $category = $report['category'] ?? 'General';
                $typeIcons = ['positive' => '✓', 'neutral' => '◉', 'concern' => '⚠'];
                ?>
                <div class="behavior-report <?php echo htmlspecialchars($reportType); ?>" data-type="<?php echo htmlspecialchars($reportType); ?>">
                  <div class="report-header">
                    <div class="report-info">
                      <div class="teacher-details">
                        <span class="reporter"><?php echo htmlspecialchars($teacherName); ?></span>
                        <?php if ($teacherSubject): ?><span class="subject-badge"><?php echo htmlspecialchars($teacherSubject); ?></span><?php endif; ?>
                      </div>
                      <div class="report-meta">
                        <span class="repo-date"><?php echo htmlspecialchars($reportDate); ?></span>
                        <span class="category-badge"><?php echo htmlspecialchars($category); ?></span>
                      </div>
                    </div>
                    <div class="report-type-indicator">
                      <span class="type-badge <?php echo htmlspecialchars($reportType); ?>">
                        <?php echo $typeIcons[$reportType] ?? '◉'; ?>
                        <?php echo ucfirst($reportType); ?>
                      </span>
                    </div>
                  </div>
                  <?php if (!empty($report['title'])): ?>
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
          <!-- ........ -->
        </div>
      </div>
    </div>
  </div>
</section>