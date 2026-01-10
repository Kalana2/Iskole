<?php
// Relief Management Assignment Section
// Expecting from controller:
// - $pendingRelief: array of timetable slots + availableTeachers
// - $selectedDate: YYYY-MM-DD
// - $presentTeacherCount: int

$selectedDate = $selectedDate ?? ($_GET['date'] ?? date('Y-m-d'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
  $selectedDate = date('Y-m-d');
}

$pendingRelief = $pendingRelief ?? [];
$presentTeacherCount = $presentTeacherCount ?? 0;
$reliefError = $reliefError ?? null;
?>
<link rel="stylesheet" href="/css/reliefManagement/reliefManagement.css" />
<section class="mp-relief theme-light" aria-labelledby="relief-assign-title">
  <header class="mgmt-header">
    <div class="title-wrap">
      <h2 id="relief-assign-title">Relief Class Assignment</h2>
      <p class="subtitle">Assign available teachers to uncovered periods (<?php echo htmlspecialchars($selectedDate); ?>)</p>
    </div>
    <div class="relief-stats">
      <div class="relief-stat"><span class="relief-stat-val"><?php echo count($pendingRelief); ?></span><span class="relief-stat-label">Unassigned</span></div>
      <div class="relief-stat"><span class="relief-stat-val"><?php echo (int)$presentTeacherCount; ?></span><span class="relief-stat-label">Present</span></div>
    </div>
  </header>
  <div class="card">
    <form method="get" class="relief-form" style="margin-bottom: 12px;">
      <input type="hidden" name="tab" value="Relief" />
      <div class="form-actions" style="justify-content: flex-start; gap: 12px;">
        <label style="display:flex; align-items:center; gap:8px;">
          <span style="min-width: 90px;">Date</span>
          <input class="input" type="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>" />
        </label>
        <button type="submit" class="btn btn-ghost">Load</button>
      </div>
    </form>

    <?php if ($reliefError): ?>
      <div class="assign-log" aria-live="polite"><?php echo htmlspecialchars($reliefError); ?></div>
    <?php endif; ?>

    <form id="reliefAssignForm" class="relief-form">
      <div class="table-wrap">
        <table class="table" aria-describedby="relief-assign-title">
          <thead>
            <tr class="table-row">
              <th class="table-head">Class</th>
              <th class="table-head">Period</th>
              <th class="table-head">Absent Teacher</th>
              <th class="table-head">Select Teacher</th>
              <th class="table-head">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($pendingRelief)): ?>
              <tr class="table-row">
                <td class="table-data" colspan="5">No pending relief slots for the selected date.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($pendingRelief as $idx => $row): ?>
              <?php
                $classLabel = trim((string)($row['grade'] ?? '')) !== ''
                  ? ((string)$row['grade'] . ' - ' . (string)($row['section'] ?? ''))
                  : (string)($row['classID'] ?? '');
                $available = $row['availableTeachers'] ?? [];
              ?>
              <tr
                data-row-index="<?php echo $idx; ?>"
                data-timetable-id="<?php echo (int)$row['timetableID']; ?>"
                data-day-id="<?php echo (int)$row['dayID']; ?>"
                data-period-id="<?php echo (int)$row['periodID']; ?>"
                class="table-row relief-row"
              >
                <td class="table-data" data-col="class"><?php echo htmlspecialchars($classLabel); ?></td>
                <td class="table-data" data-col="period"><?php echo htmlspecialchars((string)$row['periodID']); ?></td>
                <td class="table-data" data-col="absent"><?php echo htmlspecialchars((string)($row['absentTeacherName'] ?? '')); ?></td>
                <td class="table-data" data-col="teacher">
                  <select class="input input--select teacher-select" aria-label="Select teacher for period <?php echo htmlspecialchars((string)$row['periodID']); ?> class <?php echo htmlspecialchars($classLabel); ?>">
                    <option value="" selected disabled><?php echo empty($available) ? 'No free teachers' : 'Select Teacher'; ?></option>
                    <?php foreach ($available as $t): ?>
                      <option value="<?php echo (int)$t['teacherID']; ?>">
                        <?php echo htmlspecialchars($t['name'] . (isset($t['subjectName']) && $t['subjectName'] ? ' (' . $t['subjectName'] . ')' : '')); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td class="table-data status-cell" data-col="status"><span class="status-badge pending">Pending</span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="form-actions">
        <span class="spacer"></span>
        <button type="button" id="clearSelections" class="btn btn-ghost" aria-label="Clear selections">Clear</button>
        <button type="submit" class="btn btn-primary" aria-label="Assign selected relief periods">Assign Selected</button>
      </div>
    </form>
    <div class="assign-log" id="assignLog" aria-live="polite"></div>
  </div>
</section>
<script>
(function(){
  const form = document.getElementById('reliefAssignForm');
  const logEl = document.getElementById('assignLog');
  const selectedDate = <?php echo json_encode($selectedDate); ?>;
  function updateStatus(rowEl, status, cls){
    const badge = rowEl.querySelector('.status-badge');
    badge.textContent = status;
    badge.className = 'status-badge ' + cls;
  }
  form.querySelectorAll('.teacher-select').forEach(sel => {
    sel.addEventListener('change', e => {
      const row = e.target.closest('tr');
      if (e.target.value) {
        updateStatus(row, 'Ready', 'ready');
      } else {
        updateStatus(row, 'Pending', 'pending');
      }
    });
  });
  document.getElementById('clearSelections').addEventListener('click', () => {
    form.querySelectorAll('.teacher-select').forEach(sel => { sel.value = ''; });
    form.querySelectorAll('tr.relief-row').forEach(r => updateStatus(r, 'Pending', 'pending'));
    logEl.textContent = 'Selections cleared.';
  });
  form.addEventListener('submit', e => {
    e.preventDefault();
    const data = [];
    form.querySelectorAll('tr.relief-row').forEach(r => {
      const teacherSel = r.querySelector('.teacher-select');
      if (teacherSel.value) {
        data.push({
          timetableID: Number(r.dataset.timetableId),
          dayID: Number(r.dataset.dayId),
          periodID: Number(r.dataset.periodId),
          reliefTeacherID: Number(teacherSel.value),
          reliefDate: selectedDate
        });
      }
    });
    if (!data.length){
      logEl.textContent = 'No assignments selected.';
      return;
    }
    fetch('/relief/assign', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({assignments: data})
    }).then(r => r.ok ? r.json() : Promise.reject()).then(resp => {
      if (!resp || !resp.success) {
        logEl.textContent = 'Failed to save assignments.';
        return;
      }
      logEl.textContent = `Assignments saved: ${resp.saved || 0}.`;
      form.querySelectorAll('tr.relief-row').forEach(r => {
        const sel = r.querySelector('.teacher-select');
        if (sel && sel.value) updateStatus(r, 'Assigned', 'assigned');
      });
    }).catch(() => { logEl.textContent = 'Failed to save assignments.'; });
  });
})();
</script>