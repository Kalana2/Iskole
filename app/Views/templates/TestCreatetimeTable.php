<?php
// Admin timetable create/edit form and preview
$grades = $grades ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachers = $teachersMapping ?? [];
$selectedGrade = $selectedGrade ?? '';
$selectedClass = $selectedClass ?? '';
$days = $days ?? [];
if (empty($days)) {
    $days = [
        ['id' => 1, 'day' => 'Monday'],
        ['id' => 2, 'day' => 'Tuesday'],
        ['id' => 3, 'day' => 'Wednesday'],
        ['id' => 4, 'day' => 'Thursday'],
        ['id' => 5, 'day' => 'Friday']
    ];
}
$periods = $periods ?? [];
if (empty($periods)) {
  // Use keys expected by the template: 'label' and 'time'.
  $periods = [
    ['label' => 1, 'time' => '07:50 - 08:30'],
    ['label' => 2, 'time' => '08:30 - 09:10'],
    ['label' => 3, 'time' => '09:10 - 09:50'],
    ['label' => 4, 'time' => '09:50 - 10:30'],
    ['label' => 5, 'time' => '10:50 - 11:30'],
    ['label' => 6, 'time' => '11:30 - 12:10'],
    ['label' => 7, 'time' => '12:10 - 12:50'],
    ['label' => 8, 'time' => '12:50 - 13:30']
  ];
}
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
          <div class="info-item">
            <span class="info-label">Grade</span>
            <select name="grade" id="gradeSelect" class="info-value" required>
              <option value="">Select Grade</option>
              <?php if (empty($grades)): ?>
                <option value="" disabled>No grades available</option>
              <?php else: ?>
                <?php foreach ($grades as $grade): ?>
                  <option value="<?php echo $grade['value']; ?>" <?php echo $selectedGrade === $grade['value'] ? 'selected' : ''; ?>>
                    Grade <?php echo $grade['label']; ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <div class="info-item">
            <span class="info-label">Class</span>
            <select name="classId" id="sectionSelect" class="info-value" required>
              <option value="">Select Class</option>
              <?php if (empty($classes)): ?>
                <option value="" disabled>No classes available</option>
              <?php else: ?>
                <?php foreach ($classes as $class): ?>
                  <?php // $class may be either an ID or a name depending on model; support both ?>
                  <?php $classValue = is_array($class) && isset($class['classID']) ? $class['classID'] : (is_array($class) && isset($class['class']) ? $class['class'] : $class); ?>
                  <?php $classLabel = is_array($class) && isset($class['class']) ? $class['class'] : $class; ?>
                  <option value="<?php echo $classValue; ?>" <?php echo (string)$selectedClass === (string)$classValue ? 'selected' : ''; ?>>
                    Class <?php echo $classLabel; ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <form id="timetableForm" method="post" action="/admin/timetable/save">
      <input type="hidden" name="classId" id="classInput" />
      <input type="hidden" name="grade" id="gradeInput" />
      <input type="hidden" name="section" id="sectionInput" />

      <div class="builder-grid">
        <table class="timetable-table">
          <thead>
            <tr>
              <th class="time-column sticky-column">Time</th>
              <?php foreach ($days as $d): ?>
                <th class="day-column">
                  <div class="day-header">
                    <span class="day-name"><?= htmlspecialchars($d['day']) ?></span>
                  </div>
                </th>
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
                        <div class="subject-name">
                          <select class="subject-select" name="cells[<?= $d['id'] ?>][<?= $rowIndex ?>][subjectId]">
                            <option value="">Subject</option>
                            <?php if (empty($subjects)): ?>
                              <option value="" disabled>No subjects available</option>
                            <?php else: ?>
                              <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['subjectID']; ?>">
                                  <?php echo htmlspecialchars($subject['subjectName']); ?>
                                </option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                        </div>
                        <div class="class-details">
                          <span class="teacher-name">
                            <i class="fas fa-user-tie"></i>
                            <select class="teacher-select" name="cells[<?= $d['id'] ?>][<?= $rowIndex ?>][teacherId]" required>
                              <option value="">Teacher</option>
                              <?php foreach ($teachers as $subId => $tlist): ?>
                                <?php foreach ($tlist as $t): ?>
                                  <option value="<?= $t['teacherID']; ?>" data-subject="<?= $subId; ?>" style="display:none;">
                                    <?= htmlspecialchars($t['name']); ?>
                                  </option>
                                <?php endforeach; ?>
                              <?php endforeach; ?>
                            </select>
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
                <th class="day-column"><div class="day-header"><span class="day-name"><?= htmlspecialchars($d['day']) ?></span></div></th>
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
                        <div class="subject-name" data-prev="subject" data-day="<?= $d['id'] ?>" data-row="<?= $rowIndex ?>"></div>
                        <div class="class-details">
                          <span class="teacher-name"><i class="fas fa-user-tie"></i> <span data-prev="teacher" data-day="<?= $d['id'] ?>" data-row="<?= $rowIndex ?>"></span></span>
                          <!-- Room preview removed -->
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
        <div class="legend-item"><i class="fas fa-door-open"></i><span>Room Number</span></div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  const gradeSel = document.getElementById('gradeSelect');
  const sectionSel = document.getElementById('sectionSelect');
  const classInput = document.getElementById('classInput');
  const gradeInput = document.getElementById('gradeInput');
  const sectionInput = document.getElementById('sectionInput');
  const previewBtn = document.getElementById('previewBtn');
  const previewSection = document.getElementById('previewSection');

  function syncMeta(){
    const g = gradeSel?.value || '';
    const c = sectionSel?.value || '';
    if(classInput) classInput.value = c; // classId expected by controller
    if(gradeInput) gradeInput.value = g;
    if(sectionInput) sectionInput.value = c;
  }
  gradeSel?.addEventListener('change', syncMeta);
  sectionSel?.addEventListener('change', syncMeta);
  syncMeta();

  function fillPreview(){
    const preview = document.getElementById('previewTable');
    const inputs = document.querySelectorAll('[name^="cells["]');
    preview.querySelectorAll('[data-prev]')?.forEach(el => el.textContent = '');

    inputs.forEach(inp => {
      const m = inp.name.match(/^cells\[(.*?)\]\[(\d+)\]\[(subjectId|teacherId)\]$/);
      if(!m) return;
      const day = m[1];
      const row = m[2];
      const field = m[3];
      const key = field.replace(/Id$/, ''); // subjectId -> subject
      let value = '';
      if (inp.tagName === 'SELECT') {
        const opt = inp.options[inp.selectedIndex];
        value = opt ? opt.text : '';
      } else {
        value = inp.value;
      }
      const sel = `[data-prev="${key}"][data-day="${CSS.escape(day)}"][data-row="${row}"]`;
      const target = preview.querySelector(sel);
      if(target) target.textContent = value;
    });
  }

  previewBtn?.addEventListener('click', () => {
    fillPreview();
    if(previewSection){
      previewSection.style.display = 'block';
      previewSection.scrollIntoView({behavior:'smooth'});
    }
  });
})();


document.addEventListener('change', function (e) {
  if (!e.target.classList.contains('subject-select')) return;

  const subjectId = e.target.value;
  const cell = e.target.closest('.class-card');
  const teacherSelect = cell.querySelector('.teacher-select');

  // Reset teacher dropdown
  teacherSelect.value = '';

  [...teacherSelect.options].forEach(option => {
    if (!option.dataset.subject) return;

    option.style.display =
      option.dataset.subject === subjectId ? 'block' : 'none';
  });
});

</script>