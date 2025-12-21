<?php

require_once __DIR__ . '/../Core/Controller.php';

class CreateTimetableController extends Controller
{
	public function create()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['error' => 'Method not allowed']);
			return;
		}

		$input = $_POST;
		$raw = file_get_contents('php://input');
		if (empty($input) && $raw) {
			$decoded = json_decode($raw, true);
			if (is_array($decoded)) $input = $decoded;
		}

		$teacherId = $input['teacherId'] ?? null;
		$dayId = $input['dayId'] ?? null;
		$periodId = $input['periodId'] ?? null;
		$classId = $input['classId'] ?? null;
		$subjectId = $input['subjectId'] ?? null;

		if (!$teacherId || !$dayId || !$periodId || !$classId || !$subjectId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$ok = $model->insertSlot($teacherId, $dayId, $periodId, $classId, $subjectId);

		if ($ok) {
			echo json_encode(['success' => true, 'message' => 'Slot created']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to create slot']);
		}
	}
}
