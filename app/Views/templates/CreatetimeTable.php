<?php
// Admin timetable create/edit form and preview
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$periods = [
  ['time' => '07:50 - 08:30', 'label' => '1'],
  ['time' => '08:30 - 09:10', 'label' => '2'],
  ['time' => '09:10 - 09:50', 'label' => '3'],
  ['time' => '09:50 - 10:30', 'label' => '4'],
  ['time' => '10:30 - 10:50', 'label' => 'INTERVAL'],
  ['time' => '10:50 - 11:30', 'label' => '5'],
  ['time' => '11:30 - 12:10', 'label' => '6'],
  ['time' => '12:10 - 12:50', 'label' => '7'],
  ['time' => '12:50 - 01:30', 'label' => '8']
];
?>
<link rel="stylesheet" href="/css/CreateTimeTable/CreateTimeTable.css">
<link rel="stylesheet" href="/css/studentTimetable/studentTimetable.css">

<section class="admin-timetable-builder student-timetable-section">
  <div class="timetable-container">
    <div class="timetable-header">
      <div class="header-content">
        <div>
          <h1 class="header-title">Create / Edit Student Timetable</h1>
          <p class="header-subtitle">Manage class schedule. Preview matches student timetable.</p>
        </div>
        <div class="student-info-badge">
          <div class="info-item">
            <span class="info-label">Grade</span>
            <select id="gradeSelect" class="info-value">
              <option value="10">Grade 10</option>
              <option value="11">Grade 11</option>
              <option value="12">Grade 12</option>
            </select>
          </div>
          <div class="info-item">
            <span class="info-label">Class</span>
            <select id="sectionSelect" class="info-value">
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
            </select>
          </div>
          <!-- Removed previous combined grade select and week input -->
        </div>
      </div>
    </div>

    <form id="timetableForm" method="post" action="/admin/timetable/save">
      <input type="hidden" name="class" id="classInput" />
      <input type="hidden" name="grade" id="gradeInput" />
      <input type="hidden" name="section" id="sectionInput" />

      <div class="builder-grid">
        <table class="timetable-table">
          <thead>
            <tr>
              <th class="time-column sticky-column">Time</th>
              <?php foreach ($days as $d): ?>
                <th class="day-column">
                  <div class="day-header"><span class="day-name"><?= $d ?></span></div>
                </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($periods as $rowIndex => $p): ?>
              <tr class="<?= $p['label'] === 'INTERVAL' ? 'interval-row' : '' ?>">
                <td class="time-cell sticky-column">
                  <?php if ($p['label'] === 'INTERVAL'): ?>
                    <div class="interval-time"><i class="fas fa-coffee"></i><span><?= $p['time'] ?></span></div>
                  <?php else: ?>
                    <div class="time-slot"><span class="period-number">Period <?= $p['label'] ?></span><span class="time-range"><?= $p['time'] ?></span></div>
                  <?php endif; ?>
                </td>
                <?php if ($p['label'] === 'INTERVAL'): ?>
                  <td colspan="5" class="interval-cell">
                    <div class="interval-content"><i class="fas fa-mug-hot"></i><span>INTERVAL</span><i class="fas fa-utensils"></i></div>
                  </td>
                <?php else: ?>
                  <?php foreach ($days as $colIndex => $d): ?>
                    <td class="class-cell">
                      <div class="class-card">
                        <div class="subject-name">
                          <input name="cells[<?= $d ?>][<?= $rowIndex ?>][subject]" placeholder="Subject" />
                        </div>
                        <div class="class-details">
                          <span class="teacher-name">
                            <i class="fas fa-user-tie"></i>
                            <input name="cells[<?= $d ?>][<?= $rowIndex ?>][teacher]" placeholder="Teacher" />
                          </span>
                          <!-- Room input removed -->
                        </div>
                      </div>
                    </td>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="builder-actions" style="margin-top:16px; display:flex; gap:12px;">
        <button type="button" id="previewBtn" class="btn primary">Preview</button>
        <button type="submit" class="btn success">Save</button>
        <button type="reset" class="btn">Clear</button>
      </div>
    </form>

    <div id="previewSection" class="timetable-wrapper" style="display:none; margin-top:24px;">
      <h3 class="legend-title">Preview</h3>
      <div class="timetable-scroll">
        <table class="timetable-table" id="previewTable">
          <thead>
            <tr>
              <th class="time-column sticky-column">Time</th>
              <?php foreach ($days as $d): ?>
                <th class="day-column">
                  <div class="day-header"><span class="day-name"><?= $d ?></span></div>
                </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($periods as $rowIndex => $p): ?>
              <tr class="<?= $p['label'] === 'INTERVAL' ? 'interval-row' : '' ?>">
                <td class="time-cell sticky-column">
                  <?php if ($p['label'] === 'INTERVAL'): ?>
                    <div class="interval-time"><i class="fas fa-coffee"></i><span><?= $p['time'] ?></span></div>
                  <?php else: ?>
                    <div class="time-slot"><span class="period-number">Period <?= $p['label'] ?></span><span class="time-range"><?= $p['time'] ?></span></div>
                  <?php endif; ?>
                </td>
                <?php if ($p['label'] === 'INTERVAL'): ?>
                  <td colspan="5" class="interval-cell">
                    <div class="interval-content"><i class="fas fa-mug-hot"></i><span>INTERVAL</span><i class="fas fa-utensils"></i></div>
                  </td>
                <?php else: ?>
                  <?php foreach ($days as $colIndex => $d): ?>
                    <td class="class-cell">
                      <div class="class-card">
                        <div class="subject-name" data-prev="subject" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>"></div>
                        <div class="class-details">
                          <span class="teacher-name"><i class="fas fa-user-tie"></i> <span data-prev="teacher" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>"></span></span>
                        </div>
                      </div>
                    </td>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="timetable-legend">
      <h3 class="legend-title">Legend</h3>
      <div class="legend-items">
        <div class="legend-item">
          <div class="legend-color today-indicator"></div><span>Today's Classes</span>
        </div>
        <div class="legend-item">
          <div class="legend-color interval-indicator"></div><span>Break Time</span>
        </div>
        <div class="legend-item"><i class="fas fa-user-tie"></i><span>Teacher Name</span></div>
        <div class="legend-item"><i class="fas fa-door-open"></i><span>Room Number</span></div>
      </div>
    </div>
  </div>
</section>

<script>
  (function() {
    const gradeSel = document.getElementById('gradeSelect');
    const sectionSel = document.getElementById('sectionSelect');
    const classInput = document.getElementById('classInput');
    const gradeInput = document.getElementById('gradeInput');
    const sectionInput = document.getElementById('sectionInput');
    const previewBtn = document.getElementById('previewBtn');
    const previewSection = document.getElementById('previewSection');

    function syncMeta() {
      const g = gradeSel?.value || '';
      const s = sectionSel?.value || '';
      if (classInput) classInput.value = g && s ? (g + '-' + s) : '';
      if (gradeInput) gradeInput.value = g;
      if (sectionInput) sectionInput.value = s;
    }
    gradeSel?.addEventListener('change', syncMeta);
    sectionSel?.addEventListener('change', syncMeta);
    syncMeta();

    function fillPreview() {
      const preview = document.getElementById('previewTable');
      const inputs = document.querySelectorAll('[name^="cells["]');
      preview.querySelectorAll('[data-prev]')?.forEach(el => el.textContent = '');

      inputs.forEach(inp => {
        const m = inp.name.match(/^cells\[(.*?)\]\[(\d+)\]\[(subject|teacher)\]$/); // room removed
        if (!m) return;
        const day = m[1];
        const row = m[2];
        const field = m[3];
        const sel = `[data-prev="${field}"][data-day="${CSS.escape(day)}"][data-row="${row}"]`;
        const target = preview.querySelector(sel);
        if (target) target.textContent = inp.value;
      });
    }

    previewBtn?.addEventListener('click', () => {
      fillPreview();
      if (previewSection) {
        previewSection.style.display = 'block';
        previewSection.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  })();
</script>