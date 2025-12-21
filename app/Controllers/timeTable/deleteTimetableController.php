
		// Accept POST or DELETE semantics
		$id = null;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$id = $_POST['id'] ?? null;
		} else {
			$raw = file_get_contents('php://input');
			if ($raw) {
				$decoded = json_decode($raw, true);
				if (is_array($decoded)) $id = $decoded['id'] ?? null;
			}
		}

		if (!$id) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing slot ID']);
			return;
		}

		$model = $this->model('TimeTableModel');
		$ok = $model->deleteSlot($id);

		if ($ok) {
			echo json_encode(['success' => true, 'message' => 'Slot deleted']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to delete slot']);
		}
