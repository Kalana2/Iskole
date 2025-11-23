<?php
// Modern Relief Management Assignment Section
// Expecting $pendingRelief (array of [class, period]) and $teachers (array of id=>name)
// Provide sample data if not passed from controller.
if (!isset($pendingRelief)) {
    $pendingRelief = [
        ['class' => '6 - A', 'period' => 2],
        ['class' => '9 - A', 'period' => 5],
        ['class' => '7 - B', 'period' => 8],
    ];
}
if (!isset($teachers)) {
    $teachers = [
        'jinendra' => 'R K K Jinendra',
        'senuru' => 'Senuru D S Senaweera',
        'rasmsitha' => 'S K T Rasmsitha',
        'ananda' => 'R S R G A A Ananda',
    ];
}
?>
<link rel="stylesheet" href="/css/reliefManagement/reliefManagement.css" />
<section class="mp-relief theme-light" aria-labelledby="relief-assign-title">
  <header class="mgmt-header">
    <div class="title-wrap">
      <h2 id="relief-assign-title">Relief Class Assignment</h2>
      <p class="subtitle">Assign available teachers to uncovered periods today (<?php echo date('l, F j'); ?>)</p>
    </div>
    <div class="relief-stats">
      <div class="relief-stat"><span class="relief-stat-val"><?php echo count($pendingRelief); ?></span><span class="relief-stat-label">Unassigned</span></div>
      <div class="relief-stat"><span class="relief-stat-val"><?php echo count($teachers); ?></span><span class="relief-stat-label">Teachers</span></div>
    </div>
  </header>
  <div class="card">
    <form id="reliefAssignForm" class="relief-form">
      <div class="table-wrap">
        <table class="table" aria-describedby="relief-assign-title">
          <thead>
            <tr class="table-row">
              <th class="table-head">Class</th>
              <th class="table-head">Period</th>
              <th class="table-head">Select Teacher</th>
              <th class="table-head">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendingRelief as $idx => $row): ?>
              <tr data-row-index="<?php echo $idx; ?>" class="table-row relief-row">
                <td class="table-data" data-col="class"><?php echo htmlspecialchars($row['class']); ?></td>
                <td class="table-data" data-col="period"><?php echo htmlspecialchars($row['period']); ?></td>
                <td class="table-data" data-col="teacher">
                  <select name="assignment[<?php echo $idx; ?>][teacher]" class="input input--select teacher-select" aria-label="Select teacher for period <?php echo htmlspecialchars($row['period']); ?> class <?php echo htmlspecialchars($row['class']); ?>">
                    <option value="" selected disabled>Select Teacher</option>
                    <?php foreach ($teachers as $tid => $tname): ?>
                      <option value="<?php echo htmlspecialchars($tid); ?>"><?php echo htmlspecialchars($tname); ?></option>
                    <?php endforeach; ?>
                  </select>
                  <input type="hidden" name="assignment[<?php echo $idx; ?>][class]" value="<?php echo htmlspecialchars($row['class']); ?>" />
                  <input type="hidden" name="assignment[<?php echo $idx; ?>][period]" value="<?php echo htmlspecialchars($row['period']); ?>" />
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
          class: r.querySelector('input[name$="[class]"]').value,
          period: r.querySelector('input[name$="[period]"]').value,
          teacher: teacherSel.value
        });
      }
    });
    if (!data.length){
      logEl.textContent = 'No assignments selected.';
      return;
    }
    // Placeholder AJAX request
    fetch('/teacher/relief/assign', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({assignments: data})
    }).then(r => r.ok ? r.json() : Promise.reject()).then(resp => {
      logEl.textContent = 'Assignments saved successfully.';
      form.querySelectorAll('tr.relief-row').forEach(r => {
        const sel = r.querySelector('.teacher-select');
        if (sel.value) updateStatus(r, 'Assigned', 'assigned');
      });
    }).catch(() => { logEl.textContent = 'Failed to save assignments.'; });
  });
})();
</script>