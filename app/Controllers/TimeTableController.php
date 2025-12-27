<?php
require_once __DIR__ . '/../Core/Controller.php';

class TimeTableController extends Controller
{
	public function index()
	{
		$model = $this->model('TimeTableModel');
		$grades = $model->getGrades();
		$classes = $model->getClasses();

		$data = [
			'grades' => $grades,
			'classes' => $classes
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

		if ($grade) {
			$classes = $model->getClassesByGrade($grade);
		} else {
			$classes = $model->getClasses();
		}

		header('Content-Type: application/json');
		echo json_encode($classes);
	}
}