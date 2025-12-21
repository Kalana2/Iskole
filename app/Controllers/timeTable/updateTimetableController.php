<?php

require_once __DIR__ . '/../Core/Controller.php';

class UpdateTimetableController extends Controller
{
	public function update()
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

		$id = $input['id'] ?? null;
		$subjectId = $input['subjectId'] ?? null;
		$teacherId = $input['teacherId'] ?? null;

		if (!$id || !$subjectId || !$teacherId) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$ok = $model->updateSlot($id, $subjectId, $teacherId);

		if ($ok) {
			echo json_encode(['success' => true, 'message' => 'Slot updated']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to update slot']);
		}
	}
}
