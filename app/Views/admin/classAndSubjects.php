<?php
// Simple Class & Subject management UI
// This view contains two sections:
//  - Create Class: choose grade (1-13) and class letters (A-Z, uppercase)
//  - Create Subject: enter a subject name
// NOTE: This is a UI-only view with minimal PHP handling for immediate feedback.

$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Distinguish forms by the hidden input 'form_type'
	$form = $_POST['form_type'] ?? '';
	if ($form === 'add_class') {
		$grade = isset($_POST['grade']) ? (int)$_POST['grade'] : 0;
		$classLetters = isset($_POST['class_letters']) ? trim($_POST['class_letters']) : '';

		// Basic server-side validation
		if ($grade < 1 || $grade > 13) {
			$messages[] = ['type' => 'error', 'text' => 'Grade must be between 1 and 13.'];
		} elseif (!preg_match('/^[A-Z]+$/', $classLetters)) {
			$messages[] = ['type' => 'error', 'text' => 'Class letters must be uppercase letters A-Z (no spaces).'];
		} else {
			// TODO: persist to database via model/controller
			$messages[] = ['type' => 'success', 'text' => "Class added: Grade {$grade} - {$classLetters}"];
		}
	} elseif ($form === 'add_subject') {
		$subject = isset($_POST['subject_name']) ? trim($_POST['subject_name']) : '';
		if ($subject === '') {
			$messages[] = ['type' => 'error', 'text' => 'Subject name cannot be empty.'];
		} else {
			// TODO: persist subject
			$messages[] = ['type' => 'success', 'text' => 'Subject added: ' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8')];
		}
	}
}
?>

<section class="admin-behaviour-report class-subjects">
	<div class="card">
		<div class="mgmt-header">
			<div class="title-wrap">
				<h2>Class & Subjects</h2>
				<p class="subtitle">Create and manage classes and subjects</p>
			</div>
		</div>

		<?php if (!empty($messages)): ?>
			<div class="messages">
				<?php foreach ($messages as $m): ?>
					<div class="message <?php echo $m['type'] === 'error' ? 'message-error' : 'message-success'; ?>">
						<?php echo htmlspecialchars($m['text'], ENT_QUOTES, 'UTF-8'); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="forms-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:14px;">
			<!-- Create Class -->
			<div class="card">
				<h3>Create Class</h3>
				<form id="createClassForm" method="POST" action="" onsubmit="return validateClassForm();">
					<input type="hidden" name="form_type" value="add_class">
					<div class="form-row">
						<label for="grade">Grade</label>
						<select name="grade" id="grade">
							<?php for ($g = 1; $g <= 13; $g++): ?>
								<option value="<?php echo $g; ?>"><?php echo "Grade {$g}"; ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="form-row">
						<label for="class_letters">Class Letters</label>
						<input type="text" id="class_letters" name="class_letters" placeholder="E.g. A or AB" maxlength="4" />
						<small>Uppercase letters only. Multiple letters allowed (e.g., AB).</small>
					</div>

					<div class="actions-row">
						<button type="submit" class="btn-save">Add Class</button>
						<button type="button" class="btn-cancel" onclick="document.getElementById('createClassForm').reset();">Clear</button>
					</div>
				</form>

				<!-- Placeholder for existing classes list -->
				<div style="margin-top:12px;">
					<h4>Existing classes</h4>
					<table class="reports-table">
						<thead>
							<tr>
								<th>Grade</th>
								<th>Class</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<!-- Example static rows; replace with dynamic data -->
							<tr>
								<td>Grade 10</td>
								<td>A</td>
								<td class="td-actions"><button class="btn-edit">Edit</button><button class="btn-delete">Delete</button></td>
							</tr>
							<tr>
								<td>Grade 12</td>
								<td>B</td>
								<td class="td-actions"><button class="btn-edit">Edit</button><button class="btn-delete">Delete</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Create Subject -->
			<div class="card">
				<h3>Create Subject</h3>
				<form id="createSubjectForm" method="POST" action="" onsubmit="return validateSubjectForm();">
					<input type="hidden" name="form_type" value="add_subject">
					<div class="form-row">
						<label for="subject_name">Subject Name</label>
						<input type="text" id="subject_name" name="subject_name" placeholder="E.g. Mathematics" />
					</div>

					<div class="actions-row">
						<button type="submit" class="btn-save">Add Subject</button>
						<button type="button" class="btn-cancel" onclick="document.getElementById('createSubjectForm').reset();">Clear</button>
					</div>
				</form>

				<!-- Placeholder for existing subjects list -->
				<div style="margin-top:12px;">
					<h4>Existing subjects</h4>
					<table class="reports-table">
						<thead>
							<tr>
								<th>Subject</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Mathematics</td>
								<td class="td-actions"><button class="btn-edit">Edit</button><button class="btn-delete">Delete</button></td>
							</tr>
							<tr>
								<td>Science</td>
								<td class="td-actions"><button class="btn-edit">Edit</button><button class="btn-delete">Delete</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Client-side validation
		function validateClassForm() {
			var grade = document.getElementById('grade').value;
			var letters = document.getElementById('class_letters').value.trim();
			if (!/^[A-Z]+$/.test(letters)) {
				alert('Class letters must be uppercase letters A-Z and cannot be empty.');
				return false;
			}
			var g = parseInt(grade, 10);
			if (isNaN(g) || g < 1 || g > 13) {
				alert('Please select a valid grade between 1 and 13.');
				return false;
			}
			return true;
		}

		function validateSubjectForm() {
			var s = document.getElementById('subject_name').value.trim();
			if (s.length === 0) {
				alert('Please enter a subject name.');
				return false;
			}
			return true;
		}
	</script>
</section>