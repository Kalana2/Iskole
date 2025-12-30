<?php
require_once __DIR__ . '/../Core/Controller.php';

require_once __DIR__ . '/../Model/ReliefModel.php';

class ReliefController extends Controller
{
	public function index()
	{
		// If someone visits /relief directly, show a 404 for now.
		// Relief management currently lives under Admin tab rendering.
		header('Location: /admin?tab=Relief');
		exit;
	}

	/**
	 * GET /relief/pending?date=YYYY-MM-DD
	 * Returns: pending timetable slots + available teachers
	 */
	public function pending()
	{
		header('Content-Type: application/json');

		try {
			if (!isset($_SESSION['user_id'])) {
				echo json_encode(['success' => false, 'message' => 'Unauthorized']);
				return;
			}

			$date = $_GET['date'] ?? date('Y-m-d');
			$model = new ReliefModel();
			$slots = $model->getPendingReliefSlots($date);
			$teachers = $model->getAvailableReliefTeachers($date);

			echo json_encode([
				'success' => true,
				'date' => $date,
				'pendingRelief' => $slots,
				'teachers' => $teachers,
			]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}

	/**
	 * POST /relief/assign
	 * Body: { date: 'YYYY-MM-DD', assignments: [{timetableID, reliefTeacherID, reliefID?, status?}, ...] }
	 */
	public function assign()
	{
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
			return;
		}

		try {
			if (!isset($_SESSION['user_id'])) {
				echo json_encode(['success' => false, 'message' => 'Unauthorized']);
				return;
			}

			$raw = file_get_contents('php://input');
			$data = json_decode($raw, true);
			$date = $data['date'] ?? date('Y-m-d');
			$assignments = $data['assignments'] ?? [];

			if (empty($assignments) || !is_array($assignments)) {
				echo json_encode(['success' => false, 'message' => 'No assignments provided']);
				return;
			}

			$normalized = [];
			foreach ($assignments as $a) {
				$normalized[] = [
					'timetableID' => $a['timetableID'] ?? null,
					'reliefTeacherID' => $a['reliefTeacherID'] ?? null,
					'reliefID' => $a['reliefID'] ?? null,
					'status' => $a['status'] ?? 'assigned',
					'reliefDate' => $date,
				];
			}

			$model = new ReliefModel();
			$results = $model->saveReliefAssignments($normalized, (int) $_SESSION['user_id']);

			echo json_encode([
				'success' => true,
				'message' => 'Assignments saved',
				'results' => $results,
			]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}
}