<?php
// Admin timetable create/edit form and preview
$grades = $grades ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
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
            <select name="class" id="sectionSelect" class="info-value" required>
              <option value="">Select Class</option>
              <?php if (empty($classes)): ?>
                  <option value="" disabled>No classes available</option>
              <?php else: ?>
                <?php foreach ($classes as $class): ?>
                  <option value="<?php echo htmlspecialchars($class['value']); ?>" <?php echo $selectedClass === (string)$class['value'] ? 'selected' : ''; ?>>
                    Class <?php echo htmlspecialchars($class['label']); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <!-- Removed previous combined grade select and week input -->
        </div>
      </div>
    </div>

    <form id="timetableForm" method="post" action="/timetable/save">
      <input type="hidden" name="class" id="classInput" />
      <input type="hidden" name="grade" id="gradeInput" />
      <input type="hidden" name="section" id="sectionInput" />

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
                        <div class="subject-name">
                          <select class="subject-select" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>" name="cells[<?= $d ?>][<?= $rowIndex ?>][subject]">
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $sub): ?>
                              <option value="<?php echo htmlspecialchars($sub['value']); ?>"><?php echo htmlspecialchars($sub['label']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="class-details">
                          <span class="teacher-name">
                            <i class="fas fa-user-tie"></i>
                            <select class="teacher-select" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>" name="cells[<?= $d ?>][<?= $rowIndex ?>][teacher]" disabled>
                              <option value="">Select Teacher</option>
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
                        <div class="subject-name" data-prev="subject" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>"></div>
                        <div class="class-details">
                          <span class="teacher-name"><i class="fas fa-user-tie"></i> <span data-prev="teacher" data-day="<?= $d ?>" data-row="<?= $rowIndex ?>"></span></span>
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

  const teachersCache = new Map();

  function clearAllTeacherSelects(){
    document.querySelectorAll('.teacher-select').forEach(sel => {
      sel.innerHTML = '<option value="">Select Teacher</option>';
      sel.disabled = true;
    });
  }

  async function loadClasses(grade){
    if(!sectionSel) return;
    sectionSel.innerHTML = '<option value="">Select Class</option>';
    if(!grade) return;
    try{
      const res = await fetch(`/timetable/getClasses?grade=${encodeURIComponent(grade)}`);
      const rows = await res.json();
      rows.forEach(r => {
        const opt = document.createElement('option');
        opt.value = r.value;
        opt.textContent = `Class ${r.label}`;
        sectionSel.appendChild(opt);
      });
    }catch(e){
      console.error('Failed to load classes', e);
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'Error loading classes';
      sectionSel.appendChild(opt);
    }
  }

  async function loadTeachersForCell(subjectID, teacherSelect){
    const grade = gradeSel?.value || '';
    const classID = sectionSel?.value || '';
    if(!teacherSelect) return;
    teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
    teacherSelect.disabled = true;
    if(!subjectID || !grade || !classID) return;

    const cacheKey = `${grade}|${classID}|${subjectID}`;
    if(teachersCache.has(cacheKey)){
      const cached = teachersCache.get(cacheKey);
      cached.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.value;
        opt.textContent = t.label;
        teacherSelect.appendChild(opt);
      });
      teacherSelect.disabled = false;
      return;
    }

    try{
      const res = await fetch(`/timetable/getTeachers?subjectID=${encodeURIComponent(subjectID)}&grade=${encodeURIComponent(grade)}&classID=${encodeURIComponent(classID)}`);
      const rows = await res.json();
      teachersCache.set(cacheKey, rows);
      rows.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.value;
        opt.textContent = t.label;
        teacherSelect.appendChild(opt);
      });
      teacherSelect.disabled = false;
    }catch(e){
      console.error('Failed to load teachers', e);
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'Error loading teachers';
      teacherSelect.appendChild(opt);
      teacherSelect.disabled = true;
    }
  }

  function clearAllSubjectSelects(){
    document.querySelectorAll('.subject-select').forEach(sel => {
      sel.value = '';
    });
  }

  async function loadTimetableForSelectedClass(){
    const classID = sectionSel?.value || '';
    if(!classID) {
      clearAllSubjectSelects();
      clearAllTeacherSelects();
      return;
    }
    try{
      const res = await fetch(`/timetable/getTimetable?classID=${encodeURIComponent(classID)}`);
      const data = await res.json();
      const cells = (data && data.cells) ? data.cells : {};

      // reset
      clearAllSubjectSelects();
      clearAllTeacherSelects();

      // set subjects first
      Object.keys(cells).forEach(day => {
        const rows = cells[day] || {};
        Object.keys(rows).forEach(row => {
          const entry = rows[row] || {};
          const subj = String(entry.subjectID || '');
          const sel = document.querySelector(`.subject-select[data-day="${CSS.escape(day)}"][data-row="${CSS.escape(row)}"]`);
          if(sel instanceof HTMLSelectElement && subj){
            sel.value = subj;
          }
        });
      });

      // then load teachers per cell and set selected teacher
      const tasks = [];
      Object.keys(cells).forEach(day => {
        const rows = cells[day] || {};
        Object.keys(rows).forEach(row => {
          const entry = rows[row] || {};
          const subj = String(entry.subjectID || '');
          const tid = String(entry.teacherID || '');
          const teacherSel = document.querySelector(`.teacher-select[data-day="${CSS.escape(day)}"][data-row="${CSS.escape(row)}"]`);
          if(teacherSel instanceof HTMLSelectElement && subj){
            const p = loadTeachersForCell(subj, teacherSel).then(() => {
              if(tid) teacherSel.value = tid;
            });
            tasks.push(p);
          }
        });
      });
      await Promise.all(tasks);
    }catch(e){
      console.error('Failed to load timetable', e);
    }
  }

  function syncMeta(){
    const g = gradeSel?.value || '';
    const s = sectionSel?.value || '';
    if(classInput) classInput.value = s;
    if(gradeInput) gradeInput.value = g;
  if(sectionInput) sectionInput.value = s;
  }

  gradeSel?.addEventListener('change', async () => {
    await loadClasses(gradeSel.value);
    teachersCache.clear();
    clearAllTeacherSelects();
		clearAllSubjectSelects();
    syncMeta();
  });
  sectionSel?.addEventListener('change', () => {
    teachersCache.clear();
    clearAllTeacherSelects();
    syncMeta();
		loadTimetableForSelectedClass();
  });
  syncMeta();

  // Cell subject -> teacher dependent dropdown
  document.addEventListener('change', (e) => {
    const target = e.target;
    if(!(target instanceof HTMLSelectElement)) return;
    if(!target.classList.contains('subject-select')) return;
    const cell = target.closest('.class-card');
    const teacherSel = cell ? cell.querySelector('.teacher-select') : null;
    if(teacherSel instanceof HTMLSelectElement){
      loadTeachersForCell(target.value, teacherSel);
    }
  });

  function getSelectLabel(sel){
    if(!(sel instanceof HTMLSelectElement)) return '';
    const opt = (sel.selectedOptions && sel.selectedOptions.length)
      ? sel.selectedOptions[0]
      : (sel.selectedIndex >= 0 ? sel.options[sel.selectedIndex] : null);
    return (opt && typeof opt.textContent === 'string') ? opt.textContent.trim() : '';
  }

  function fillPreview(){
    const preview = document.getElementById('previewTable');
    const inputs = document.querySelectorAll('[name^="cells["]');
    if(!preview) return;
    preview.querySelectorAll('[data-prev]')?.forEach(el => el.textContent = '');

    inputs.forEach(inp => {
      const m = inp.name.match(/^cells\[(.*?)\]\[(\d+)\]\[(subject|teacher)\]$/); // room removed
      if(!m) return;
      const day = m[1];
      const row = m[2];
      const field = m[3];
      const sel = `[data-prev="${field}"][data-day="${CSS.escape(day)}"][data-row="${row}"]`;
      const target = preview.querySelector(sel);
		if(!target) return;
      if(inp instanceof HTMLSelectElement){
        const label = getSelectLabel(inp);
        target.textContent = (inp.value ? (label || String(inp.value).trim()) : '');
		} else {
			target.textContent = inp.value;
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
})();
</script>