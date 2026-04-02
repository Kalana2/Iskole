<?php
require_once __DIR__ . '/../Core/Controller.php';

class TimeTableController extends Controller
{
	private function rowIndexToPeriodNumberMap()
	{
		// Matches the template’s $periods array (rowIndex) where index 4 is INTERVAL.
		return [
			0 => 1,
			1 => 2,
			2 => 3,
			3 => 4,
			5 => 5,
			6 => 6,
			7 => 7,
			8 => 8,
		];
	}

	private function periodNumberToRowIndexMap()
	{
		return [
			1 => 0,
			2 => 1,
			3 => 2,
			4 => 3,
			5 => 5,
			6 => 6,
			7 => 7,
			8 => 8,
		];
	}

	public function index()
	{
		$model = $this->model('TimeTableModel');
		$grades = $model->getGrades();
		$subjects = $model->getSubjects();

		$data = [
			'grades' => $grades,
			'classes' => [],
			'subjects' => $subjects,
			'selectedGrade' => '',
        	'selectedClass' => ''
		];

		$this->view('admin/timeTable', $data);
	}

	public function getGrades()
	{
		$model = $this->model('TimeTableModel');
		$grades = $model->getGrades();

		header('Content-Type: application/json');
		echo json_encode($grades);
	}

	public function getClasses()
	{
		$model = $this->model('TimeTableModel');
		$grade = $_GET['grade'] ?? null;
		$classes = $grade ? $model->getClassesByGrade($grade) : [];

		header('Content-Type: application/json');
		echo json_encode($classes);
		exit;
	}

	public function getSubjects()
	{
		$model = $this->model('TimeTableModel');
		$subjects = $model->getSubjects();
		header('Content-Type: application/json');
		echo json_encode($subjects);
		exit;
	}

	public function getTeachers()
	{
		$model = $this->model('TimeTableModel');
		$subjectID = $_GET['subjectID'] ?? null;
		$classID = $_GET['classID'] ?? null;
		$dayName = $_GET['day'] ?? null;
		$rowIndex = $_GET['row'] ?? null;

		$dayName = is_string($dayName) ? trim($dayName) : '';
		$classID = is_numeric($classID) ? (int)$classID : 0;
		$rowIndex = is_numeric($rowIndex) ? (int)$rowIndex : null;

		if ($dayName !== '' && $classID > 0 && $rowIndex !== null) {
			$dayMap = $model->getSchoolDayNameToIdMap();
			$dayID = (int)($dayMap[$dayName] ?? 0);
			$periodNumMap = $this->rowIndexToPeriodNumberMap();
			$periodNum = (int)($periodNumMap[$rowIndex] ?? 0);
			$periodIdMap = $model->getPeriodNumberToIdMap();
			$periodID = (int)($periodIdMap[$periodNum] ?? 0);

			if ($dayID > 0 && $periodID > 0) {
				$teachers = $model->getAvailableTeachersBySubjectAndSlot($subjectID, $dayID, $periodID, $classID);
			} else {
				$teachers = $model->getTeachersBySubject($subjectID);
			}
		} else {
			$teachers = $model->getTeachersBySubject($subjectID);
		}
		header('Content-Type: application/json');
		echo json_encode($teachers);
		exit;
	}

	public function getTimetable()
	{
		$model = $this->model('TimeTableModel');
		$classID = $_GET['classID'] ?? null;

		$entries = $model->getClassTimetableEntries($classID);
		$dayNameToId = $model->getSchoolDayNameToIdMap();
		$dayIdToName = array_flip($dayNameToId);
		$periodNumToId = $model->getPeriodNumberToIdMap();
		$periodIdToNum = array_flip($periodNumToId);
		$periodNumToRow = $this->periodNumberToRowIndexMap();

		$cells = [];
		foreach ($entries as $e) {
			$dayID = (int)($e['dayID'] ?? 0);
			$periodID = (int)($e['periodID'] ?? 0);
			$subjectID = (int)($e['subjectID'] ?? 0);
			$teacherID = (int)($e['teacherID'] ?? 0);
			$dayName = $dayIdToName[$dayID] ?? null;
			$periodNum = $periodIdToNum[$periodID] ?? null;
			if (!$dayName || !$periodNum) {
				continue;
			}
			$rowIndex = $periodNumToRow[$periodNum] ?? null;
			if ($rowIndex === null) {
				continue;
			}
			if (!isset($cells[$dayName])) {
				$cells[$dayName] = [];
			}
			$cells[$dayName][(string)$rowIndex] = [
				'subjectID' => $subjectID,
				'teacherID' => $teacherID,
			];
		}

		header('Content-Type: application/json');
		echo json_encode(['cells' => $cells]);
		exit;
	}

	public function save()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo 'Method Not Allowed';
			exit;
		}

		$model = $this->model('TimeTableModel');

		$classID = $_POST['section'] ?? ($_POST['class'] ?? null);
		$grade = $_POST['grade'] ?? null;
		$cells = $_POST['cells'] ?? [];
		if (!is_array($cells)) {
			$cells = [];
		}

		$dayNameToId = $model->getSchoolDayNameToIdMap();
		$periodNumToId = $model->getPeriodNumberToIdMap();
		$rowToPeriodNum = $this->rowIndexToPeriodNumberMap();

		$entries = [];
		foreach ($cells as $dayName => $rows) {
			if (!is_array($rows)) {
				continue;
			}
			$dayID = $dayNameToId[(string)$dayName] ?? null;
			if (!$dayID) {
				continue;
			}
			foreach ($rows as $rowIndex => $cell) {
				if (!is_array($cell)) {
					continue;
				}
				$periodNum = $rowToPeriodNum[(int)$rowIndex] ?? null;
				if (!$periodNum) {
					continue;
				}
				$periodID = $periodNumToId[$periodNum] ?? null;
				if (!$periodID) {
					continue;
				}
				$subjectID = (int)($cell['subject'] ?? 0);
				$teacherID = (int)($cell['teacher'] ?? 0);
				if ($subjectID <= 0 || $teacherID <= 0) {
					continue;
				}
				$entries[] = [
					'dayID' => (int)$dayID,
					'periodID' => (int)$periodID,
					'subjectID' => $subjectID,
					'teacherID' => $teacherID,
				];
			}
		}

		try {
			$model->saveClassTimetable($classID, $entries);
			$_SESSION['tt_msg'] = 'Timetable saved.';
		} catch (Throwable $e) {
			$_SESSION['tt_msg'] = 'Save failed: ' . $e->getMessage();
		}

		// Redirect back to Admin timetable tab
		header('Location: /admin?tab=Time%20Tables');
		exit;
	}
}