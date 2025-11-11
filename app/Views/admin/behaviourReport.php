<?php
// Admin behaviour report list (tabular CRUD UI)
// Expects $behaviorReports from controller. If not provided, falls back to sample data for client-side demo.
$behaviorReports = $behaviorReports ?? [
    [
        'id' => 1,
        'student_name' => 'Seniru Senaweera',
        'teacher_name' => 'Mr. John Silva',
        'teacher_subject' => 'Mathematics',
        'report_date' => '2025-11-01',
        'report_type' => 'positive',
        'title' => 'Excellent Leadership in Group Work',
        'description' => 'Showed exceptional leadership during group activities. Actively helped struggling classmates understand complex algebra concepts.',
        'category' => 'Academic Excellence'
    ],
    [
        'id' => 2,
        'student_name' => 'Anushi Perera',
        'teacher_name' => 'Mrs. Sarah Perera',
        'teacher_subject' => 'Science',
        'report_date' => '2025-10-30',
        'report_type' => 'positive',
        'title' => 'Outstanding Lab Safety and Participation',
        'description' => 'Displayed outstanding participation in today\'s chemistry lab experiment. Followed all safety protocols perfectly.',
        'category' => 'Safety & Conduct'
    ]
];
?>
<link rel="stylesheet" href="/css/admin/behaviourAdmin.css">

<section class="admin-behaviour-report tab-panel">
    <div class="card center-container">
        <header class="mgmt-header">
            <div class="title-wrap">
                <h2>Behaviour Reports (Admin)</h2>
                <p class="subtitle">View, edit and delete behaviour reports submitted by teachers.</p>
            </div>
        </header>

        <div class="search-actions">
            <div class="search-container">
                <input id="searchInput" type="text" placeholder="Search by student or teacher name...">
                <button id="clearSearch" class="search-btn">Clear</button>
            </div>
        </div>

        <div class="table-wrap">
            <table id="reportsTable" class="reports-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Teacher</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($behaviorReports as $r):
                        $id = htmlspecialchars($r['id'] ?? '');
                        $student = htmlspecialchars($r['student_name'] ?? ($r['student'] ?? 'Unknown'));
                        $teacher = htmlspecialchars($r['teacher_name'] ?? 'Unknown');
                        $date = isset($r['report_date']) && $r['report_date'] !== '' ? date('Y-m-d', strtotime($r['report_date'])) : 'N/A';
                        $type = htmlspecialchars($r['report_type'] ?? 'neutral');
                        $category = htmlspecialchars($r['category'] ?? 'General');
                    ?>
                        <tr data-id="<?php echo $id; ?>" data-student="<?php echo $student; ?>" data-teacher="<?php echo $teacher; ?>">
                            <td class="td-student"><?php echo $student; ?></td>
                            <td class="td-teacher"><?php echo $teacher; ?></td>
                            <td class="td-date"><?php echo $date; ?></td>
                            <td class="td-type <?php echo $type; ?>"><?php echo ucfirst($type); ?></td>
                            <td class="td-category"><?php echo $category; ?></td>
                            <td class="td-actions">
                                <button class="btn-edit" data-id="<?php echo $id; ?>">Edit</button>
                                <button class="btn-delete" data-id="<?php echo $id; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        <div id="modalEdit" class="modal" style="display:none;">
            <div class="modal-content">
                <button class="modal-close">×</button>
                <h3>Edit Behaviour Report</h3>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-row">
                        <label for="edit_student">Student</label>
                        <input type="text" id="edit_student" name="student" required readonly>
                    </div>
                    <div class="form-row">
                        <label for="edit_teacher">Teacher</label>
                        <input type="text" id="edit_teacher" name="teacher" required readonly>
                    </div>
                    <div class="form-row">
                        <label for="edit_type">Report Type</label>
                        <select id="edit_type" name="report_type">
                            <option value="positive">Positive</option>
                            <option value="neutral">Neutral</option>
                            <option value="concern">Concern</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="edit_category">Category</label>
                        <input type="text" id="edit_category" name="category">
                    </div>
                    <div class="form-row">
                        <label for="edit_title">Title</label>
                        <input type="text" id="edit_title" name="title" required>
                    </div>
                    <div class="form-row">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-row actions-row">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="button" class="btn-cancel modal-close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
    // Client-side behaviour for admin table (works purely client-side if backend endpoints aren't present)
    (function() {
        // Build JS dataset from server-rendered table rows
        const buildDataFromDOM = () => {
            const rows = Array.from(document.querySelectorAll('#reportsTable tbody tr'));
            return rows.map(row => ({
                id: row.dataset.id,
                student: row.dataset.student,
                teacher: row.dataset.teacher,
                date: row.querySelector('.td-date')?.textContent || '',
                type: row.querySelector('.td-type')?.textContent.toLowerCase() || 'neutral',
                category: row.querySelector('.td-category')?.textContent || '',
                // Try to read hidden details from server-provided attributes if any
                title: row.dataset.title || '',
                description: row.dataset.description || ''
            }));
        };

        let data = buildDataFromDOM();

        // Modal helpers
        const modalDetails = document.getElementById('modalDetails');
        const modalEdit = document.getElementById('modalEdit');

        function showModal(modal) {
            modal.style.display = 'block';
        }

        function hideModal(modal) {
            modal.style.display = 'none';
        }
        document.querySelectorAll('.modal-close').forEach(b => b.addEventListener('click', e => {
            const m = e.target.closest('.modal');
            if (m) hideModal(m);
        }));

        // Render table from `data` array
        function renderTable(filterText = '') {
            const tbody = document.querySelector('#reportsTable tbody');
            tbody.innerHTML = '';
            const ft = filterText.trim().toLowerCase();
            data.forEach(r => {
                if (ft) {
                    const hay = (r.student + ' ' + r.teacher).toLowerCase();
                    if (!hay.includes(ft)) return;
                }
                const tr = document.createElement('tr');
                tr.dataset.id = r.id;
                tr.dataset.student = r.student;
                tr.dataset.teacher = r.teacher;
                tr.innerHTML = `
				<td class="td-student">${escapeHtml(r.student)}</td>
				<td class="td-teacher">${escapeHtml(r.teacher)}</td>
				<td class="td-date">${escapeHtml(r.date)}</td>
				<td class="td-type ${escapeHtml(r.type)}">${escapeHtml(cap(r.type))}</td>
				<td class="td-category">${escapeHtml(r.category)}</td>
				<td class="td-actions">
					<button class="btn-more" data-id="${r.id}">More</button>
					<button class="btn-edit" data-id="${r.id}">Edit</button>
					<button class="btn-delete" data-id="${r.id}">Delete</button>
				</td>`;
                tbody.appendChild(tr);
            });
        }

        function cap(s) {
            return s ? (s.charAt(0).toUpperCase() + s.slice(1)) : '';
        }

        function escapeHtml(s) {
            return String(s || '').replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": "&#39;"
                } [m];
            });
        }

        // Attach global table listeners (event delegation)
        document.querySelector('#reportsTable tbody').addEventListener('click', function(e) {
            const tr = e.target.closest('tr');
            if (!tr) return;
            const id = tr.dataset.id;
            if (e.target.matches('.btn-more')) {
                const record = data.find(x => x.id == id);
                if (record) {
                    document.getElementById('detailTitle').textContent = record.title || 'Report Details';
                    document.getElementById('detailBody').innerHTML = `
					<p><strong>Student:</strong> ${escapeHtml(record.student)}</p>
					<p><strong>Teacher:</strong> ${escapeHtml(record.teacher)}</p>
					<p><strong>Date:</strong> ${escapeHtml(record.date)}</p>
					<p><strong>Type:</strong> ${escapeHtml(cap(record.type))}</p>
					<p><strong>Category:</strong> ${escapeHtml(record.category)}</p>
					<hr>
					<p>${escapeHtml(record.description || 'No description')}</p>`;
                    showModal(modalDetails);
                }
            } else if (e.target.matches('.btn-edit')) {
                const record = data.find(x => x.id == id);
                if (record) {
                    document.getElementById('edit_id').value = record.id;
                    document.getElementById('edit_student').value = record.student;
                    document.getElementById('edit_teacher').value = record.teacher;
                    document.getElementById('edit_type').value = record.type;
                    document.getElementById('edit_category').value = record.category;
                    document.getElementById('edit_title').value = record.title || '';
                    document.getElementById('edit_description').value = record.description || '';
                    showModal(modalEdit);
                }
            } else if (e.target.matches('.btn-delete')) {
                if (!confirm('Delete this behaviour report? This action cannot be undone.')) return;
                // remove from data and re-render
                data = data.filter(x => x.id != id);
                renderTable(document.getElementById('searchInput').value);
                // Try to call backend delete endpoint (optional)
                fetch('/admin/behavior/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                }).catch(() => {
                    /* ignore errors - user may not have backend wired */
                });
            }
        });

        // Edit form submit handler
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            const updated = {
                id: id,
                student: document.getElementById('edit_student').value,
                teacher: document.getElementById('edit_teacher').value,
                report_type: document.getElementById('edit_type').value,
                category: document.getElementById('edit_category').value,
                title: document.getElementById('edit_title').value,
                description: document.getElementById('edit_description').value
            };
            // update local data
            data = data.map(x => x.id == id ? Object.assign({}, x, {
                type: updated.report_type,
                category: updated.category,
                title: updated.title,
                description: updated.description
            }) : x);
            renderTable(document.getElementById('searchInput').value);
            hideModal(modalEdit);

            // Try to call backend update endpoint (optional). Endpoint should accept JSON {id, report_type, category, title, description}
            fetch('/admin/behavior/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updated)
            }).catch(() => {
                /* backend may not be present - local update still applied */
            });
        });

        // Search input
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            renderTable(this.value);
        });
        document.getElementById('clearSearch').addEventListener('click', function() {
            searchInput.value = '';
            renderTable('');
        });

        // Initial render
        // If the server provided richer details (title, description) as data-attributes on rows, try to merge them into data array
        (function mergeExtras() {
            const rows = document.querySelectorAll('#reportsTable tbody tr');
            rows.forEach(r => {
                const id = r.dataset.id;
                const rec = data.find(x => x.id == id);
                if (rec) {
                    // read possible server-side attributes
                    if (r.dataset.title) rec.title = r.dataset.title;
                    if (r.dataset.description) rec.description = r.dataset.description;
                    // ensure student/teacher values also match
                    rec.student = r.dataset.student || rec.student;
                    rec.teacher = r.dataset.teacher || rec.teacher;
                }
            });
        })();

        renderTable('');
    })();
</script>

<!-- Notes:
	- The front-end works fully client-side using the $behaviorReports passed from the controller.
	- To persist edits/deletes on the server, implement endpoints:
			POST  /admin/behavior/update  (body JSON: {id, report_type, category, title, description})
			POST  /admin/behavior/delete  (body JSON: {id})
		or adapt the fetch URLs above to match your routing scheme (e.g., index.php?url=admin/behaviour/update).
	- Creating new records is intentionally not implemented for admins per request.
-->