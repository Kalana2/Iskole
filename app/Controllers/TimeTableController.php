<?php
require_once __DIR__ . '/../Core/Controller.php';

class TimeTableController extends Controller
{
	public function index()
	{
		$model = $this->model('TimeTableModel');
		$grades = $model->getGrades();
		$classes = $model->getClasses();
		$subjects = $model->getSubjects();
		$teachersMapping = $model->getTeachersMapping();

		$this->view('admin/timeTable', [
			'grades' => $grades,
			'classes' => $classes,
			'subjects' => $subjects,
			'teachersMapping' => $teachersMapping,
			'selectedGrade' => '',
			'selectedClass' => ''
		]);
	}

	// Load timetable for a specific class
	public function getTimetable($classId = null)
	{
		$model = $this->model('TimeTableModel');
		$grades = $model->getGrades();
		$classes = $model->getClasses();
		$subjects = $model->getSubjects();
		$teachersMapping = $model->getTeachersMapping();

		$timetable = [];
		if ($classId) {
			$timetable = $model->getTimetableByClass($classId);
		}

		$this->view('admin/timeTable', [
			'grades' => $grades,
			'classes' => $classes,
			'subjects' => $subjects,
			'teachersMapping' => $teachersMapping,
			'selectedGrade' => '',
			'selectedClass' => $classId ?? '',
			'timetable' => $timetable
		]);
	}

	// Save or update a timetable slot
	public function save()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['error' => 'Method not allowed']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$teacherId = $_POST['teacherId'] ?? null;
		$dayId = $_POST['dayId'] ?? null;
		$periodId = $_POST['periodId'] ?? null;
		$classId = $_POST['classId'] ?? null;
		$subjectId = $_POST['subjectId'] ?? null;

		if (!$teacherId || !$dayId || !$periodId || !$classId || !$subjectId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields']);
			return;
		}

		$result = $model->insertSlot($teacherId, $dayId, $periodId, $classId, $subjectId);

		if ($result) {
			echo json_encode(['success' => true, 'message' => 'Slot saved successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to save slot']);
		}
	}

	// Update an existing slot
	public function update()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['error' => 'Method not allowed']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$id = $_POST['id'] ?? null;
		$subjectId = $_POST['subjectId'] ?? null;
		$teacherId = $_POST['teacherId'] ?? null;

		if (!$id || !$subjectId || !$teacherId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields']);
			return;
		}

		$result = $model->updateSlot($id, $subjectId, $teacherId);

		if ($result) {
			echo json_encode(['success' => true, 'message' => 'Slot updated successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to update slot']);
		}
	}

	// Delete a slot
	public function delete()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['error' => 'Method not allowed']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$id = $_POST['id'] ?? null;

		if (!$id) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing slot ID']);
			return;
		}

		$result = $model->deleteSlot($id);

		if ($result) {
			echo json_encode(['success' => true, 'message' => 'Slot deleted successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to delete slot']);
		}
	}

	// API: get teachers by subject
	public function getTeachersBySubject()
	{
		header('Content-Type: application/json');
		$subjectId = $_GET['subjectId'] ?? null;

		if (!$subjectId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing subjectId']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$teachers = $model->getTeachersBySubject($subjectId);

		echo json_encode(['teachers' => $teachers]);
	}
}

