<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Model/reliefModel.php';

class ReliefController extends Controller
{
	private reliefModel $reliefModel;

	public function __construct()
	{
		parent::__construct();
		$this->reliefModel = new reliefModel();
	}

	/**
	 * JSON: pending relief slots for a date.
	 * GET /relief/pending?date=YYYY-MM-DD
	 */
	public function pending()
	{
		header('Content-Type: application/json');

		$date = $_GET['date'] ?? date('Y-m-d');
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
			http_response_code(400);
			echo json_encode(['success' => false, 'message' => 'Invalid date format']);
			return;
		}

		try {
			$slots = $this->reliefModel->getPendingReliefSlots($date);
			echo json_encode(['success' => true, 'date' => $date, 'slots' => $slots]);
		} catch (Exception $e) {
			error_log('Relief pending error: ' . $e->getMessage());
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => 'Failed to load relief slots']);
		}
	}

	/**
	 * JSON: create relief assignments.
	 * POST /relief/assign
	 * Body: { assignments: [ { timetableID, dayID, periodID, reliefTeacherID, reliefDate } ] }
	 */
	public function assign()
	{
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
			return;
		}

		if (!isset($_SESSION['user_id'])) {
			http_response_code(401);
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$raw = file_get_contents('php://input');
		$payload = json_decode($raw, true);
		$assignments = $payload['assignments'] ?? [];

		if (!is_array($assignments) || empty($assignments)) {
			http_response_code(400);
			echo json_encode(['success' => false, 'message' => 'No assignments provided']);
			return;
		}

		$createdBy = (int)$_SESSION['user_id'];
		$saved = 0;
		$errors = [];

		try {
			foreach ($assignments as $i => $a) {
				$timetableId = (int)($a['timetableID'] ?? 0);
				$dayId = (int)($a['dayID'] ?? 0);
				$periodId = (int)($a['periodID'] ?? 0);
				$reliefTeacherId = (int)($a['reliefTeacherID'] ?? 0);
				$reliefDate = (string)($a['reliefDate'] ?? '');

				if ($timetableId <= 0 || $dayId <= 0 || $periodId <= 0 || $reliefTeacherId <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $reliefDate)) {
					$errors[] = ['index' => $i, 'message' => 'Invalid assignment payload'];
					continue;
				}

				if (!$this->reliefModel->isTeacherFree($reliefDate, $reliefTeacherId, $dayId, $periodId)) {
					$errors[] = ['index' => $i, 'message' => 'Teacher not available'];
					continue;
				}

				$ok = $this->reliefModel->createReliefAssignment($timetableId, $reliefTeacherId, $reliefDate, $dayId, $periodId, $createdBy);
				if ($ok) {
					$saved++;
				} else {
					$errors[] = ['index' => $i, 'message' => 'Insert failed'];
				}
			}

			echo json_encode([
				'success' => $saved > 0,
				'saved' => $saved,
				'errors' => $errors
			]);
		} catch (Exception $e) {
			error_log('Relief assign error: ' . $e->getMessage());
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => 'Failed to save assignments']);
		}
	}
}

