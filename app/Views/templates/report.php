<?php

$behaviorReports = $behaviorReports ?? [];
$flash = $flash ?? null;
$student = $student ?? null;
$suggestions = $suggestions ?? [];

$editReport = $editReport ?? null;
$isEdit = !empty($editReport);


$formType     = $isEdit ? ($editReport['report_type'] ?? 'positive') : 'positive';
$formCategory = $isEdit ? ($editReport['category'] ?? '') : '';
$formTitle    = $isEdit ? ($editReport['title'] ?? '') : '';
$formDesc     = $isEdit ? ($editReport['description'] ?? '') : '';
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

      <!-- Search -->
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
            spellcheck="false"
            autocapitalize="off"
            autocorrect="off"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            onfocus="this.value=''; this.dispatchEvent(new Event('input'));">

          <datalist id="studentList">
            <?php foreach (($suggestions ?? []) as $s): ?>
              <option value="<?= htmlspecialchars(($s['studentID'] ?? '') . ' - ' . trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? ''))) ?>">
                <?= htmlspecialchars(trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? '')) . ' (' . ($s['studentID'] ?? '') . ')') ?>
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
              <h2 class="student-name">
                <?= htmlspecialchars(trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? '')) ?: 'N/A') ?>
              </h2>

              <div class="info-grid">
                <div class="info-item"><span class="label">Grade:</span>
                  <span class="value"><?= htmlspecialchars($student['grade'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item"><span class="label">Class:</span>
                  <span class="value"><?= htmlspecialchars($student['className'] ?? 'N/A') ?></span>

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

        <!-- Performance Overview (your static UI) -->
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

          <!-- ✅ Add / Edit Form -->
          <div class="behavior-form-wrapper">
            <h3 class="report-title"><?= $isEdit ? 'Edit Behavior Report' : 'Add Behavior Report' ?></h3>

            <div class="behavior-update">
              <form action="/index.php?url=report/<?= $isEdit ? 'update' : 'submit' ?>" method="POST">

                <?php if ($isEdit): ?>
                  <input type="hidden" name="report_id" value="<?= htmlspecialchars($editReport['id']) ?>">
                <?php endif; ?>

                <?php if (!empty($student)): ?>
                  <input type="hidden" name="studentID" value="<?= htmlspecialchars($student['studentID']) ?>">
                <?php endif; ?>

                <div class="form-row">
                  <label for="report_type">Report Type</label>
                  <select id="report_type" name="report_type" required>
                    <option value="positive" <?= $formType === 'positive' ? 'selected' : '' ?>>Positive</option>
                    <option value="neutral" <?= $formType === 'neutral' ? 'selected' : '' ?>>Neutral</option>
                    <option value="concern" <?= $formType === 'concern' ? 'selected' : '' ?>>Concern</option>
                  </select>
                </div>

                <div class="form-row">
                  <label for="category">Category</label>
                  <input type="text" id="category" name="category"
                    value="<?= htmlspecialchars($formCategory) ?>"
                    placeholder="e.g. Academic Excellence" required />
                </div>

                <div class="form-row">
                  <label for="title">Title</label>
                  <input type="text" id="title" name="title"
                    value="<?= htmlspecialchars($formTitle) ?>"
                    placeholder="e.g. Excellent Leadership in Group Work" required />
                </div>

                <div class="form-row">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" rows="4"
                    placeholder="Enter detailed observation..." required><?= htmlspecialchars($formDesc) ?></textarea>
                </div>

                <button type="submit" class="update-behavior-btn">
                  <?= $isEdit ? '✏️ Update Report' : '➕ Add Report' ?>
                </button>

                <?php if ($isEdit): ?>
                  <a class="cancel-btn"
                    href="/index.php?url=teacher&tab=Reports<?= !empty($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>">
                    ❌ Cancel
                  </a>

                <?php endif; ?>

              </form>
            </div>
          </div>


          <!-- ✅ Reports List -->
          <div class="behavior-report-list">
            <h3 class="report-title">Recent Behavior Reports</h3>

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
                $typeIcons = ['positive' => '✓', 'neutral' => '◉', 'concern' => '⚠'];

                $rid = $report['report_id'] ?? $report['id'] ?? '';
                ?>

                <div class="behavior-report <?= htmlspecialchars($reportType) ?>" data-type="<?= htmlspecialchars($reportType) ?>">
                  <div class="report-header">
                    <div class="report-info">
                      <div class="teacher-details">
                        <span class="reporter"><?= htmlspecialchars($teacherName) ?></span>
                        <?php if ($teacherSubject): ?>
                          <span class="subject-badge"><?= htmlspecialchars($teacherSubject) ?></span>
                        <?php endif; ?>
                      </div>

                      <div class="report-meta">
                        <span class="repo-date"><?= htmlspecialchars($reportDate) ?></span>
                        <span class="category-badge"><?= htmlspecialchars($category) ?></span>
                      </div>
                    </div>

                    <div class="report-type-indicator">
                      <span class="type-badge <?= htmlspecialchars($reportType) ?>">
                        <?= $typeIcons[$reportType] ?? '◉' ?>
                        <?= ucfirst($reportType) ?>
                      </span>
                    </div>

                    <!-- ✅ ACTIONS: Edit + Delete -->
                    <div class="report-actions">

                      <!-- EDIT -->
                      <form method="POST" action="/index.php?url=report/edit" style="display:inline-block;">
                        <input type="hidden" name="report_id" value="<?= htmlspecialchars($rid) ?>">
                        <input type="hidden" name="tab" value="Reports">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit" class="edit-btn">✏️ Edit</button>
                      </form>

                      <!-- DELETE -->
                      <form method="POST"
                        action="/index.php?url=report/delete"
                        onsubmit="return confirm('Are you sure you want to delete this report?');"
                        style="display:inline-block;">
                        <input type="hidden" name="report_id" value="<?= htmlspecialchars($rid) ?>">
                        <input type="hidden" name="tab" value="Reports">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit" class="delete-btn">🗑 Delete</button>
                      </form>

                    </div>
                  </div>

                  <?php if (!empty($report['title'])): ?>
                    <div class="report-title">
                      <?= htmlspecialchars($report['title']) ?>
                    </div>
                  <?php endif; ?>

                  <div class="report-content">
                    <p><?= htmlspecialchars($report['description'] ?? 'No description provided') ?></p>
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

        </div>
      </div>
    </div>
  </div>
</section>

<script>
  window.addEventListener("load", () => {
    const dl = document.getElementById("studentList");
    if (!dl) return;

    // clone & replace = clears browser cached datalist entries
    const newDl = dl.cloneNode(true);
    dl.parentNode.replaceChild(newDl, dl);
  });
</script>