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
            //$selectedExamType = $_POST['examType'] ?? '';}

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
	}
}}

