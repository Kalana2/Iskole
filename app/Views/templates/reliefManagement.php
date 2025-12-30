<?php
// Relief Management Assignment Section
// Data is loaded via AJAX from /relief/pending for the selected date.
?>
<link rel="stylesheet" href="/css/reliefManagement/reliefManagement.css" />
<section class="mp-relief theme-light" aria-labelledby="relief-assign-title">
  <header class="mgmt-header">
    <div class="title-wrap">
      <h2 id="relief-assign-title">Relief Class Assignment</h2>
      <p class="subtitle">Assign available teachers to uncovered periods today (<span id="reliefDateLabel"><?php echo date('l, F j'); ?></span>)</p>
    </div>
    <div class="relief-stats">
      <div class="relief-stat"><span class="relief-stat-val" id="unassignedCount">0</span><span class="relief-stat-label">Unassigned</span></div>
      <div class="relief-stat"><span class="relief-stat-val" id="teacherCount">0</span><span class="relief-stat-label">Teachers</span></div>
    </div>
  </header>
  <div class="card">
    <form id="reliefAssignForm" class="relief-form">
      <div class="table-wrap">
        <table class="table" aria-describedby="relief-assign-title">
          <thead>
            <tr class="table-row">
              <th class="table-head">Class</th>
              <th class="table-head">Day</th>
              <th class="table-head">Period</th>
              <th class="table-head">Absent Teacher</th>
              <th class="table-head">Select Teacher</th>
              <th class="table-head">Status</th>
            </tr>
          </thead>
          <tbody id="reliefRows"></tbody>
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
  const rowsEl = document.getElementById('reliefRows');
  const unassignedCountEl = document.getElementById('unassignedCount');
  const teacherCountEl = document.getElementById('teacherCount');
  const reliefDateLabelEl = document.getElementById('reliefDateLabel');

  const todayISO = new Date().toISOString().slice(0,10);
  let currentDate = todayISO;
  let teachers = [];
  let slots = [];

  function updateStatus(rowEl, status, cls){
    const badge = rowEl.querySelector('.status-badge');
    badge.textContent = status;
    badge.className = 'status-badge ' + cls;
  }

  function formatPrettyDate(iso){
    try {
      const d = new Date(iso + 'T00:00:00');
      return d.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' });
    } catch { return iso; }
  }

  function buildTeacherOptions(selectedId){
    const opts = ['<option value="" selected disabled>Select Teacher</option>'];
    teachers.forEach(t => {
      const id = String(t.teacherID);
      const name = (t.teacherName || '').trim() || ('Teacher ' + id);
      const sel = selectedId && String(selectedId) === id ? ' selected' : '';
      opts.push(`<option value="${id}"${sel}>${escapeHtml(name)}</option>`);
    });
    return opts.join('');
  }

  function escapeHtml(str){
    return String(str)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'",'&#039;');
  }

  function render(){
    reliefDateLabelEl.textContent = formatPrettyDate(currentDate);
    unassignedCountEl.textContent = String(slots.length);
    teacherCountEl.textContent = String(teachers.length);

    if (!slots.length){
      rowsEl.innerHTML = `<tr class="table-row"><td class="table-data" colspan="6">No uncovered periods for today.</td></tr>`;
      return;
    }

    rowsEl.innerHTML = slots.map((row, idx) => {
      const timetableID = row.timetableID;
      const classLabel = row.classLabel || '';
      const day = row.dayOfWeek || (row.dayID ? String(row.dayID) : '');
      const period = row.periodID || row.period || '';
      const absentTeacherName = row.absentTeacherName || '';
      const existingTeacher = row.reliefTeacherID || '';
      const status = row.assignmentStatus ? 'Assigned' : (existingTeacher ? 'Ready' : 'Pending');
      const statusClass = row.assignmentStatus ? 'assigned' : (existingTeacher ? 'ready' : 'pending');
      return `
        <tr data-row-index="${idx}" data-timetable-id="${escapeHtml(timetableID)}" class="table-row relief-row">
          <td class="table-data" data-col="class">${escapeHtml(classLabel)}</td>
          <td class="table-data" data-col="day">${escapeHtml(day)}</td>
          <td class="table-data" data-col="period">${escapeHtml(period)}</td>
          <td class="table-data" data-col="absentTeacher">${escapeHtml(absentTeacherName)}</td>
          <td class="table-data" data-col="teacher">
            <select class="input input--select teacher-select" aria-label="Select relief teacher">
              ${buildTeacherOptions(existingTeacher)}
            </select>
          </td>
          <td class="table-data status-cell" data-col="status"><span class="status-badge ${statusClass}">${escapeHtml(status)}</span></td>
        </tr>
      `;
    }).join('');

    rowsEl.querySelectorAll('.teacher-select').forEach(sel => {
      sel.addEventListener('change', e => {
        const row = e.target.closest('tr');
        if (e.target.value) updateStatus(row, 'Ready', 'ready');
        else updateStatus(row, 'Pending', 'pending');
      });
    });
  }

  async function load(){
    logEl.textContent = 'Loading relief data...';
    try {
      const r = await fetch(`/relief/pending?date=${encodeURIComponent(currentDate)}`);
      const json = await r.json();
      if (!r.ok || !json.success) throw new Error(json.message || 'Failed');
      teachers = json.teachers || [];
      slots = json.pendingRelief || [];
      render();
      logEl.textContent = '';
    } catch (e){
      rowsEl.innerHTML = `<tr class="table-row"><td class="table-data" colspan="6">Failed to load relief data.</td></tr>`;
      logEl.textContent = (e && e.message) ? e.message : 'Failed to load relief data.';
    }
  }

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
      const timetableID = r.getAttribute('data-timetable-id');
      if (teacherSel && teacherSel.value && timetableID) {
        data.push({
          timetableID: timetableID,
          reliefTeacherID: teacherSel.value,
          status: 'assigned'
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
      body: JSON.stringify({date: currentDate, assignments: data})
    }).then(r => r.json().then(j => ({ok: r.ok, json: j})))
      .then(({ok, json}) => {
        if (!ok || !json.success) throw new Error(json.message || 'Failed to save');
        logEl.textContent = 'Assignments saved successfully.';
        form.querySelectorAll('tr.relief-row').forEach(r => {
          const sel = r.querySelector('.teacher-select');
          if (sel && sel.value) updateStatus(r, 'Assigned', 'assigned');
        });
      })
      .catch((e) => {
        logEl.textContent = (e && e.message) ? e.message : 'Failed to save assignments.';
      });
  });

  load();
})();
</script>