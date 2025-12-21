<?php

require_once __DIR__ . '/../Core/Controller.php';

class ReadTimetableController extends Controller
{
	public function read()
	{
		// Accept GET (query) or POST (body) for flexibility
		$classId = $_GET['classId'] ?? null;

		if (!$classId) {
			$raw = file_get_contents('php://input');
			if ($raw) {
				$decoded = json_decode($raw, true);
				if (is_array($decoded) && isset($decoded['classId'])) {
					$classId = $decoded['classId'];
				}
			}
		}

		if (!$classId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing classId']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$timetable = $model->getTimetableByClass($classId);

		header('Content-Type: application/json');
		echo json_encode(['classId' => $classId, 'timetable' => $timetable]);
	}
}
