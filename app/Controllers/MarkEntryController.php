<?php
require_once __DIR__ . '/../Core/Controller.php';

class MarkEntryController extends Controller
{
	public function index()
	{
		$model = $this->model('MarkEntryModel');

		// Defaults (empty/select state)
		$selectedGrade = '';
		$selectedClass = '';
		$selectedTerm = '';
		$message = null;

		$teacherInfo = $model->getTeacherInfo($this->session->get('user_id'));

		// Handle marks submission
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marks']) && is_array($_POST['marks'])) {
			$marks = $_POST['marks'];
			$meta = [
				'grade' => $_POST['grade'] ?? null,
				'class' => $_POST['class'] ?? null,
				'term' => $_POST['term'] ?? null,
				'teacherUserId' => $this->session->get('user_id'),
				'teacherID' => $teacherInfo['teacher_id'] ?? null,
				'subjectID' => $teacherInfo['subjectID'] ?? null
			];

			try {
				$ok = $model->saveMarks($marks, $meta);
				$message = $ok ? 'Marks saved successfully.' : 'Failed to save marks.';
			} catch (Exception $e) {
				$message = 'Error saving marks: ' . $e->getMessage();
			}

			// Preserve selections after submit
			$selectedGrade = $meta['grade'] ?? '';
			$selectedClass = $meta['class'] ?? '';
			$selectedTerm = $meta['term'] ?? '';
            //$selectedExamType = $meta['examType'] ?? '';
		}

		// Handle filter form (load students)
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grade']) && isset($_POST['class']) && !isset($_POST['marks'])) {
			$selectedGrade = $_POST['grade'];
			$selectedClass = $_POST['class'];
			$selectedTerm = $_POST['term'] ?? '';
		}

		$grades = $model->getGrades();
		$classes = $model->getClasses($selectedGrade ?: null);
		$terms = $model->getTerms();
		//$examTypes = $model->getExamTypes();

		$students = [];
		if ($selectedGrade && $selectedClass) {
			$students = $model->getStudents($selectedGrade, $selectedClass, $teacherInfo['subjectID'] ?? null, $selectedTerm ?: null);
		}

		$stats = $model->calculateStatistics($students);

		$data = [
			'teacherInfo' => $teacherInfo,
			'grades' => $grades,
			'classes' => $classes,
			'terms' => $terms,
			//'examTypes' => $examTypes,
			'selectedGrade' => $selectedGrade,
			'selectedClass' => $selectedClass,
			'selectedTerm' => $selectedTerm,
			//'selectedExamType' => $selectedExamType,
			'students' => $students,
			'totalStudents' => $stats['totalStudents'],
			'marksEntered' => $stats['marksEntered'],
			'marksPending' => $stats['marksPending'],
			'completionPercentage' => $stats['completionPercentage'],
			'classAverage' => $stats['classAverage'],
			'message' => $message
		];

		$this->view('templates/markEntry', $data);

		//header("Location: ".($_SERVER['HTTP_REFERER'] ?? "/teacher?tab=Mark+Entry"));
		//exit();
	}

	public function loadStudents()
	{
		// AJAX endpoint: returns student data as JSON without page refresh
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(400);
			echo json_encode(['error' => 'Invalid request method']);
			return;
		}

		$grade = $_POST['grade'] ?? null;
		$class = $_POST['class'] ?? null;
		$term = $_POST['term'] ?? null;

		if (!$grade || !$class) {
			http_response_code(400);
			echo json_encode(['error' => 'Grade and class are required']);
			return;
		}

		$model = $this->model('MarkEntryModel');
		$teacherInfo = $model->getTeacherInfo($this->session->get('user_id'));
		$students = $model->getStudents($grade, $class, $teacherInfo['subjectID'] ?? null, $term ?: null);
		$stats = $model->calculateStatistics($students);

		echo json_encode([
			'success' => true,
			'students' => $students,
			'stats' => $stats,
			'selectedGrade' => $grade,
			'selectedClass' => $class,
			'selectedTerm' => $term
		]);
	}

	public function submitMarks()
	{
		// AJAX endpoint: accept marks and return updated students + stats
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(400);
			echo json_encode(['success' => false, 'error' => 'Invalid request method']);
			return;
		}

		if (!isset($_POST['marks']) || !is_array($_POST['marks'])) {
			http_response_code(400);
			echo json_encode(['success' => false, 'error' => 'Missing marks data']);
			return;
		}

		$marks = $_POST['marks'];
		$grade = $_POST['grade'] ?? null;
		$class = $_POST['class'] ?? null;
		$term = $_POST['term'] ?? null;

		$model = $this->model('MarkEntryModel');
		$teacherInfo = $model->getTeacherInfo($this->session->get('user_id'));

		$meta = [
			'grade' => $grade,
			'class' => $class,
			'term' => $term,
			'teacherUserId' => $this->session->get('user_id'),
			'teacherID' => $teacherInfo['teacher_id'] ?? null,
			'subjectID' => $teacherInfo['subjectID'] ?? null
		];

		try {
			$ok = $model->saveMarks($marks, $meta);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'error' => 'Error saving marks: ' . $e->getMessage()]);
			return;
		}

		// Fetch updated list + stats and return to client for UI update
		$students = [];
		if ($grade && $class) {
			$students = $model->getStudents($grade, $class, $teacherInfo['subjectID'] ?? null, $term ?: null);
		}

		$stats = $model->calculateStatistics($students);

		echo json_encode([
			'success' => true,
			'message' => $ok ? 'Marks saved successfully.' : 'Failed to save marks.',
			'students' => $students,
			'stats' => $stats,
			'selectedGrade' => $grade,
			'selectedClass' => $class,
			'selectedTerm' => $term
		]);
	}

	public function deleteMarks()
	{
		// AJAX endpoint: delete marks from database
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(400);
			echo json_encode(['error' => 'Invalid request method']);
			return;
		}

		$studentId = $_POST['studentId'] ?? null;
		$grade = $_POST['grade'] ?? null;
		$class = $_POST['class'] ?? null;
		$term = $_POST['term'] ?? null;

		if (!$studentId || !$grade || !$class || !$term) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required parameters']);
			return;
		}

		$model = $this->model('MarkEntryModel');
		$teacherInfo = $model->getTeacherInfo($this->session->get('user_id'));

		try {
			$ok = $model->deleteMarks($studentId, $teacherInfo['subjectID'] ?? null, $term);
			if ($ok) {
				echo json_encode(['success' => true, 'message' => 'Marks deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'error' => 'Failed to delete marks']);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'error' => $e->getMessage()]);
		}
	}
}