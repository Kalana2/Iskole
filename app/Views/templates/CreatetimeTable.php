<?php
// Admin timetable create/edit form and preview
$grades = $grades ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachersMapping = $teachersMapping ?? [];
$timetable = $timetable ?? [];

$days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
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
          <h1 class="header-title">Create / Edit Class Timetable</h1>
          <p class="header-subtitle">Manage class schedule. Preview matches student timetable.</p>
        </div>
        <div class="student-info-badge">
            <div class = "info-item">
            <label for="grade" class="info-label">Grade</label>
            <select name="grade" id="grade" class="info-value" required>
                <option value="">Select Grade</option>
                <?php if (empty($grades)): ?>
                    <option value="" disabled>No grades available</option>
                <?php else: ?>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?php echo $grade['value']; ?>" <?php echo ($selectedGrade ?? '') === $grade['value'] ? 'selected' : ''; ?>>
                            Grade <?php echo $grade['label']; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            </div>

            <div class = "info-item">
            <label for="class" class="info-label">Class</label>
              <select name="class" id="class" class="info-value" required>
                  <option value="">Select Class</option>
                  <?php if (empty($classes)): ?>
                      <option value="" disabled>No classes available</option>
                  <?php else: ?>
                      <?php foreach ($classes as $class): ?>
                          <option value="<?php echo $class; ?>" <?php echo ($selectedClass ?? '') === $class ? 'selected' : ''; ?>>
                              Class <?php echo $class; ?>
                          </option>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </select>
            </div>
        </div>
      </div>
    </div>

    <form id="timetableForm" method="post" action="/admin/timetable/save">
      <input type="hidden" name="class" id="classInput" />
      <input type="hidden" name="grade" id="gradeInput" />

      <div class="builder-grid">
        <table class="timetable-table">
          <thead>
            <tr>
              <th class="time-column sticky-column">Time</th>
              <?php foreach ($days as $d): ?>
                <th class="day-column"><div class="day-header"><span class="day-name"><?= $d ?></span></div></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($periods as $rowIndex => $p): ?>
              <tr class="<?= $p['label']==='INTERVAL' ? 'interval-row' : '' ?>">
                <td class="time-cell sticky-column">
                  <?php if ($p['label']==='INTERVAL'): ?>
                    <div class="interval-time"><i class="fas fa-coffee"></i><span><?= $p['time'] ?></span></div>
                  <?php else: ?>
                    <div class="time-slot"><span class="period-number">Period <?= $p['label'] ?></span><span class="time-range"><?= $p['time'] ?></span></div>
                  <?php endif; ?>
                </td>
                <?php if ($p['label']==='INTERVAL'): ?>
                  <td colspan="5" class="interval-cell">
                    <div class="interval-content"><i class="fas fa-mug-hot"></i><span>INTERVAL</span><i class="fas fa-utensils"></i></div>
                  </td>
                <?php else: ?>
                  <?php foreach ($days as $colIndex => $d): ?>
                    <td class="class-cell">
                      <div class="class-card">
                        <!--<div class="subject-name">
                          <select name="cells[<?= $d ?>][<?= $rowIndex ?>][subject]" class="subject-select" required>
                              <option value="">Subject</option>
                              <?php if (!empty($subjects)): ?>
                                  <?php foreach ($subjects as $subject): ?>
                                      <option value="<?php echo $subject['subjectID']; ?>">
                                          <?php echo $subject['subjectName']; ?>
                                      </option>
                                  <?php endforeach; ?>
                              <?php endif; ?>
                          </select>
                        </div>
                        <div class="class-details">
                          <span class="teacher-name">
                            <i class="fas fa-user-tie"></i>
                            <select name="cells[<?= $d ?>][<?= $rowIndex ?>][teacher]" class="teacher-select" required>
                                <option value="">Teacher</option>
                            </select>
                          </span>
                        </div>-->
                        <div class="subject-name">
                          <select name="subject" id="subject" class="form-select" required>
                            <option value="">Subject</option>
                            <?php if (empty($subjects)): ?>
                                <option value="" disabled>No subjects available</option>
                            <?php else: ?>
                                <?php foreach ($subjects as $subject): ?>
                                  <option value="<?php echo $subject['value']; ?>" <?php echo $selectedSubject === $subject['value'] ? 'selected' : ''; ?>>
                                      <?php echo $subject['label']; ?>
                                  </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                        </div>

                        <div class="teacher-name">
                          <select name="teacher" id="teacher" class="form-select" required>
                            <option value="">Teacher</option>
                            <?php if (empty($teachers)): ?>
                                <option value="" disabled>No teachers available</option>
                            <?php else: ?>
                                <?php foreach ($subjects as $subject): ?>
                                  <option value="<?php echo $subject['value']; ?>" <?php echo $selectedSubject === $subject['value'] ? 'selected' : ''; ?>>
                                      <?php echo $subject['label']; ?>
                                  </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
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
                <th class="day-column"><div class="day-header"><span class="day-name"><?= $d ?></span></div></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($periods as $rowIndex => $p): ?>
              <tr class="<?= $p['label']==='INTERVAL' ? 'interval-row' : '' ?>">
                <td class="time-cell sticky-column">
                  <?php if ($p['label']==='INTERVAL'): ?>
                    <div class="interval-time"><i class="fas fa-coffee"></i><span><?= $p['time'] ?></span></div>
                  <?php else: ?>
                    <div class="time-slot"><span class="period-number">Period <?= $p['label'] ?></span><span class="time-range"><?= $p['time'] ?></span></div>
                  <?php endif; ?>
                </td>
                <?php if ($p['label']==='INTERVAL'): ?>
                  <td colspan="5" class="interval-cell">
                    <div class="interval-content"><i class="fas fa-mug-hot"></i><span>INTERVAL</span><i class="fas fa-utensils"></i></div>
                  </td>
                <?php else: ?>
                  <?php foreach ($days as $colIndex => $d): ?>
                    <td class="class-cell">
                      <div class="class-card">
                        <div class="subject-name" data-prev="subject" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>" style="font-weight: bold; min-height: 20px;"></div>
                        <div class="class-details">
                          <span class="teacher-name"><i class="fas fa-user-tie"></i> <span data-prev="teacher" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>" style="margin-left: 5px;"></span></span>
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
        <div class="legend-item"><div class="legend-color today-indicator"></div><span>Today's Classes</span></div>
        <div class="legend-item"><div class="legend-color interval-indicator"></div><span>Break Time</span></div>
        <div class="legend-item"><i class="fas fa-user-tie"></i><span>Teacher Name</span></div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  // Teachers mapping injected from server as JSON (safe-escaped)
  const teachersMapping = <?php 
    $tm = $teachersMapping ?? [];
    echo json_encode($tm, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_SLASHES);
  ?>;
  const subjectsData = <?php 
    $sd = $subjects ?? [];
    echo json_encode($sd, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_SLASHES);
  ?>;

  const gradeSelect = document.getElementById('grade');
  const classSelect = document.getElementById('class');
  const classInput = document.getElementById('classInput');
  const gradeInput = document.getElementById('gradeInput');
  const previewBtn = document.getElementById('previewBtn');
  const previewSection = document.getElementById('previewSection');
  const timetableForm = document.getElementById('timetableForm');

  // Update hidden inputs when grade/class change
  function syncMeta(){
    const g = gradeSelect?.value || '';
    const c = classSelect?.value || '';
    if(gradeInput) gradeInput.value = g;
    if(classInput) classInput.value = c;
  }
  gradeSelect?.addEventListener('change', syncMeta);
  classSelect?.addEventListener('change', syncMeta);
  syncMeta();

  // When a subject is selected, populate the corresponding teacher select
  timetableForm.addEventListener('change', function(e) {
    const subjectSelect = e.target.closest('.subject-select');
    if (!subjectSelect) return;

    const subjectId = subjectSelect.value;
    const cellCard = subjectSelect.closest('.class-card');
    const teacherSelect = cellCard.querySelector('.teacher-select');

    if (!teacherSelect) return;

    // Clear existing options except the placeholder
    while (teacherSelect.options.length > 1) {
      teacherSelect.remove(1);
    }

    // If no subject selected, leave empty
    if (!subjectId) {
      return;
    }

    // Populate teacher options for this subject
    const teachersForSubject = teachersMapping[subjectId] || [];
    if (teachersForSubject.length === 0) {
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'No teachers available';
      opt.disabled = true;
      teacherSelect.appendChild(opt);
      return;
    }

    teachersForSubject.forEach(function(teacher) {
      const opt = document.createElement('option');
      opt.value = teacher.teacherID;
      opt.textContent = teacher.name;
      teacherSelect.appendChild(opt);
    });
  });

  // Fill preview table from form selections
  function fillPreview(){
    const preview = document.getElementById('previewTable');
    const inputs = document.querySelectorAll('[name^="cells["]');
    preview.querySelectorAll('[data-prev]')?.forEach(el => el.textContent = '');

    // Create a map of selected values for lookup
    const selectedMap = {};
    inputs.forEach(inp => {
      const m = inp.name.match(/^cells\[(.*?)\]\[(\d+)\]\[(subject|teacher)\]$/);
      if(!m) return;
      const day = m[1];
      const row = m[2];
      const field = m[3];
      const key = `${day}_${row}`;
      if(!selectedMap[key]) selectedMap[key] = {};
      selectedMap[key][field] = inp.value;
    });

    // Map subject IDs to subject names for preview
    const subjectMap = {};
    subjectsData.forEach(s => {
      subjectMap[s.subjectID] = s.subjectName;
    });

    // Update preview elements
    inputs.forEach(inp => {
      const m = inp.name.match(/^cells\[(.*?)\]\[(\d+)\]\[(subject|teacher)\]$/);
      if(!m) return;
      const day = m[1];
      const row = m[2];
      const field = m[3];
      const val = inp.value;
      
      // Escape day for querySelector - handle special chars
      const escapedDay = day.replace(/[\"\\]/g, '\\$&');
      const sel = `[data-prev="${field}"][data-day="${escapedDay}"][data-row="${row}"]`;
      const target = preview.querySelector(sel);
      
      if(target) {
        if (field === 'subject') {
          // Show subject name instead of ID
          target.textContent = val ? (subjectMap[val] || val) : '';
        } else if (field === 'teacher') {
          // Find and show teacher name
          if (!val) {
            target.textContent = '';
            return;
          }
          let teacherName = val;
          for (const subId in teachersMapping) {
            if (!teachersMapping[subId]) continue;
            const found = teachersMapping[subId].find(t => String(t.teacherID) === String(val));
            if (found) {
              teacherName = found.name;
              break;
            }
          }
          target.textContent = teacherName;
        }
      }
    });
  }

  previewBtn?.addEventListener('click', () => {
    fillPreview();
    if(previewSection){
      previewSection.style.display = 'block';
      previewSection.scrollIntoView({behavior:'smooth'});
    }
  });

  // Optional: auto-fill preview on form changes
  timetableForm.addEventListener('change', fillPreview);
})();
</script>
