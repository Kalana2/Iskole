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
              <option value="<?= htmlspecialchars(($s['studentID'] ?? '') . ' - ' . trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? ''))) ?>"></option>
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

        <!-- ✅ Performance Overview + Chart (the missing part) -->
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
              <button class="toggle-btn active" type="button" data-chart="line">Trend</button>
              <button class="toggle-btn" type="button" data-chart="radar">Radar Chart</button>
            </div>
          </h3>
          <div class="chart-container">
            <canvas id="performanceChart"></canvas>
          </div>
        </div>

        <!-- Behavior Section -->
        <div class="behavior-section">

          <!-- Add / Edit Form -->
          <div class="behavior-form-wrapper">
            <h3 class="report-title"><?= $isEdit ? 'Edit Behavior Report' : 'Add Behavior Report' ?></h3>

            <div class="behavior-update">
              <form action="/index.php?url=report/<?= $isEdit ? 'update' : 'submit' ?>" method="POST">
                <?php if ($isEdit): ?>
                  <input type="hidden" name="report_id" value="<?= htmlspecialchars($editReport['id'] ?? '') ?>">
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
                  <button type="button" class="cancel-btn" id="cancelEditBtn">❌ Cancel</button>
                <?php endif; ?>
              </form>
            </div>
          </div>

          <!-- Reports List -->
          <div class="behavior-report-list">
            <h3 class="report-title">Recent Behavior Reports</h3>

            <?php if (!empty($behaviorReports)): ?>
              <?php foreach ($behaviorReports as $report): ?>
                <?php
                $reportDate = !empty($report['report_date']) ? date('F j, Y', strtotime($report['report_date'])) : 'N/A';
                $reportType = $report['report_type'] ?? 'neutral';
                $teacherName = $report['teacher_name'] ?? 'Unknown Teacher';
                $teacherSubject = $report['teacher_subject'] ?? '';
                $category = $report['category'] ?? 'General';
                $typeIcons = ['positive' => '✓', 'neutral' => '◉', 'concern' => '⚠'];
                $rid = $report['id'] ?? $report['report_id'] ?? '';
                ?>

                <div class="behavior-report <?= htmlspecialchars($reportType) ?>"
                     data-report-id="<?= htmlspecialchars($rid) ?>"
                     data-type="<?= htmlspecialchars($reportType) ?>">

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
                        <span class="category-badge js-category"><?= htmlspecialchars($category) ?></span>
                      </div>
                    </div>

                    <div class="report-type-indicator">
                      <span class="type-badge <?= htmlspecialchars($reportType) ?> js-typeBadge">
                        <?= $typeIcons[$reportType] ?? '◉' ?>
                        <span class="js-typeText"><?= ucfirst($reportType) ?></span>
                      </span>
                    </div>

                    <!-- ✅ NO RELOAD buttons -->
                    <div class="report-actions">
                      <button type="button"
                              class="edit-btn js-edit"
                              data-report-id="<?= htmlspecialchars($rid) ?>">
                        ✏️ Edit
                      </button>

                      <button type="button"
                              class="delete-btn js-delete"
                              data-report-id="<?= htmlspecialchars($rid) ?>">
                        🗑 Delete
                      </button>
                    </div>
                  </div>

                  <?php if (!empty($report['title'])): ?>
                    <div class="report-title js-title">
                      <?= htmlspecialchars($report['title']) ?>
                    </div>
                  <?php endif; ?>

                  <div class="report-content">
                    <p class="js-desc"><?= htmlspecialchars($report['description'] ?? 'No description provided') ?></p>
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

<!-- EDIT MODAL -->
<div id="editModal" class="report-modal" aria-hidden="true">
  <div class="report-modal-backdrop" data-close="1"></div>
  <div class="report-modal-card" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <h3 id="editModalTitle" style="margin:0 0 10px;">Edit Behavior Report</h3>

    <form id="editForm">
      <input type="hidden" name="report_id" id="edit_report_id">

      <div class="form-row">
        <label for="edit_report_type">Report Type</label>
        <select id="edit_report_type" name="report_type" required>
          <option value="positive">Positive</option>
          <option value="neutral">Neutral</option>
          <option value="concern">Concern</option>
        </select>
      </div>

      <div class="form-row">
        <label for="edit_category">Category</label>
        <input type="text" id="edit_category" name="category" required>
      </div>

      <div class="form-row">
        <label for="edit_title">Title</label>
        <input type="text" id="edit_title" name="title" required>
      </div>

      <div class="form-row">
        <label for="edit_description">Description</label>
        <textarea id="edit_description" name="description" rows="4" required></textarea>
      </div>

      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
        <button type="button" class="cancel-btn" id="editCancelBtn">❌ Cancel</button>
        <button type="submit" class="update-behavior-btn">✏️ Update</button>
      </div>
    </form>
  </div>
</div>

<style>
  .report-modal{ position:fixed; inset:0; display:none; z-index:9999; }
  .report-modal.show{ display:block; }
  .report-modal-backdrop{ position:absolute; inset:0; background:rgba(0,0,0,.35); }
  .report-modal-card{
    position:relative;
    width:min(640px, 92vw);
    margin: 6vh auto 0;
    background:#fff;
    border-radius:16px;
    padding:16px;
    box-shadow:0 18px 60px rgba(0,0,0,.25);
  }
  .cancel-btn{ text-decoration:none; border:none; cursor:pointer; }
</style>

<script>
  // clear cached datalist
  window.addEventListener("load", () => {
    const dl = document.getElementById("studentList");
    if (!dl) return;
    const newDl = dl.cloneNode(true);
    dl.parentNode.replaceChild(newDl, dl);
  });

  // Cancel edit mode (server session)
  const cancelEditBtn = document.getElementById('cancelEditBtn');
  if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', () => {
      // simple way: reload without edit session (your controller already unsets on load)
      window.location.href = '/index.php?url=teacher&tab=Reports' + (new URLSearchParams(window.location.search).get('q') ? '&q=' + encodeURIComponent(new URLSearchParams(window.location.search).get('q')) : '');
    });
  }

  // AJAX helpers
  (function () {
    const modal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    const openModal = () => { modal.classList.add('show'); modal.setAttribute('aria-hidden','false'); };
    const closeModal = () => { modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); };

    modal.addEventListener('click', (e) => { if (e.target?.dataset?.close === "1") closeModal(); });
    document.getElementById('editCancelBtn').addEventListener('click', closeModal);

    async function postJSON(url, formDataObj) {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formDataObj
      });
      return res.json();
    }

    // DELETE (no reload)
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('.js-delete');
      if (!btn) return;

      const reportId = btn.dataset.reportId;
      if (!reportId) return;

      if (!confirm('Are you sure you want to delete this report?')) return;

      const fd = new FormData();
      fd.append('report_id', reportId);

      const data = await postJSON('/index.php?url=report/delete', fd);

      if (data.ok) {
        const card = document.querySelector(`.behavior-report[data-report-id="${reportId}"]`);
        if (card) card.remove();
      } else {
        alert(data.message || 'Delete failed.');
      }
    });

    // OPEN EDIT modal (no reload)
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('.js-edit');
      if (!btn) return;

      const reportId = btn.dataset.reportId;
      if (!reportId) return;

      const fd = new FormData();
      fd.append('report_id', reportId);

      const data = await postJSON('/index.php?url=report/edit', fd);

      if (!data.ok) {
        alert(data.message || 'Cannot open edit.');
        return;
      }

      document.getElementById('edit_report_id').value = data.report.id;
      document.getElementById('edit_report_type').value = data.report.report_type || 'neutral';
      document.getElementById('edit_category').value = data.report.category || '';
      document.getElementById('edit_title').value = data.report.title || '';
      document.getElementById('edit_description').value = data.report.description || '';

      openModal();
    });

    // UPDATE (no reload)
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const fd = new FormData(editForm);
      const data = await postJSON('/index.php?url=report/update', fd);

      if (!data.ok) {
        alert(data.message || 'Update failed.');
        return;
      }

      const r = data.report;
      const card = document.querySelector(`.behavior-report[data-report-id="${r.id}"]`);
      if (card) {
        const titleEl = card.querySelector('.js-title');
        const descEl = card.querySelector('.js-desc');
        const catEl = card.querySelector('.js-category');
        const typeBadge = card.querySelector('.js-typeBadge');
        const typeText = card.querySelector('.js-typeText');

        if (titleEl) titleEl.textContent = r.title || '';
        if (descEl) descEl.textContent = r.description || '';
        if (catEl) catEl.textContent = r.category || 'General';

        card.classList.remove('positive','neutral','concern');
        card.classList.add(r.report_type);

        if (typeBadge) {
          typeBadge.classList.remove('positive','neutral','concern');
          typeBadge.classList.add(r.report_type);
        }
        if (typeText) {
          const t = r.report_type || '';
          typeText.textContent = t ? (t.charAt(0).toUpperCase() + t.slice(1)) : '';
        }
      }

      closeModal();
    });
  })();
</script>
