<?php
require_once __DIR__ . '/../Core/Controller.php';

class AcademicOverviewController extends Controller
{
	private function jsonResponse($payload, int $status = 200): void
	{
		http_response_code($status);
		header('Content-Type: application/json');
		echo json_encode($payload);
		exit;
	}

	public function getGrades(): void
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->jsonResponse(['success' => false, 'message' => 'Method Not Allowed'], 405);
		}
		try {
			$model = $this->model('AcademicOverviewModel');
			$grades = $model->getGrades();
			$this->jsonResponse(['success' => true, 'data' => $grades]);
		} catch (Throwable $e) {
			error_log('AcademicOverviewController@getGrades: ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'message' => 'Failed to load grades'], 500);
		}
	}

	public function getClasses(): void
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->jsonResponse(['success' => false, 'message' => 'Method Not Allowed'], 405);
		}

		$gradeRaw = $_GET['grade'] ?? null;
		$grade = is_numeric($gradeRaw) ? (int)$gradeRaw : 0;
		if ($grade <= 0) {
			$this->jsonResponse(['success' => false, 'message' => 'Invalid grade'], 400);
		}

		try {
			$model = $this->model('AcademicOverviewModel');
			$classes = $model->getClassesByGrade($grade);
			$this->jsonResponse(['success' => true, 'data' => $classes]);
		} catch (Throwable $e) {
			error_log('AcademicOverviewController@getClasses: ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'message' => 'Failed to load classes'], 500);
		}
	}

	public function getSubjects(): void
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->jsonResponse(['success' => false, 'message' => 'Method Not Allowed'], 405);
		}
		try {
			$model = $this->model('AcademicOverviewModel');
			$subjects = $model->getSubjects();
			$this->jsonResponse(['success' => true, 'data' => $subjects]);
		} catch (Throwable $e) {
			error_log('AcademicOverviewController@getSubjects: ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'message' => 'Failed to load subjects'], 500);
		}
	}

	public function getSubjectAverages(): void
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->jsonResponse(['success' => false, 'message' => 'Method Not Allowed'], 405);
		}

		$classIdRaw = $_GET['classID'] ?? null;
		$classID = is_numeric($classIdRaw) ? (int)$classIdRaw : 0;
		if ($classID <= 0) {
			$this->jsonResponse(['success' => false, 'message' => 'Invalid classID'], 400);
		}

		$term = isset($_GET['term']) ? (string)$_GET['term'] : null;
		$term = $term !== null ? trim($term) : null;

		try {
			$model = $this->model('AcademicOverviewModel');
			$rows = $model->getSubjectAveragesByClassId($classID, $term);
			$this->jsonResponse(['success' => true, 'data' => $rows]);
		} catch (Throwable $e) {
			error_log('AcademicOverviewController@getSubjectAverages: ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'message' => 'Failed to load subject averages'], 500);
		}
	}
}

