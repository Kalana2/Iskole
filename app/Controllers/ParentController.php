<?php
require_once __DIR__ . '/../Core/Controller.php';

class ParentController extends Controller
{
    public function index()
    {
        // Handle announcement actions
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }

        $this->view('parent/index');
    }

    private function handleAnnouncementAction($action)
    {
        switch ($action) {
            case 'delete':
                include_once __DIR__ . '/announcement/deleteAnnouncementController.php';
                break;
            case 'update':
                include_once __DIR__ . '/announcement/updateAnnouncementController.php';
                break;
        }
    }

    public function requests()
    {
        // Get the logged-in user's ID
        $userId = $this->session ? $this->session->get('user_id') : ($_SESSION['user_id'] ?? null);

        if (!$userId) {
            $_SESSION['mgmt_msg'] = 'User not authenticated.';
            header('Location: /login');
            exit;
        }

        // Get parent information
        $parentModel = $this->model('ParentModel');
        $parent = $parentModel->getParentByUserId($userId);

        $formattedRequests = [];

        if ($parent) {
            // Get absence reasons for this parent
            $absenceModel = $this->model('StudentAbsenceReasonModel');
            $recentRequests = $absenceModel->getAbsenceReasonsByParentId($parent['parentID']);
            var_dump($recentRequests); // Debugging line
            sleep(10); // Pause to allow inspection of var_dump output

            // Transform data to match view expectations
            if (!empty($recentRequests)) {
                foreach ($recentRequests as $req) {
                    $formattedRequests[] = [
                        'id' => $req['reasonID'] ?? 0,
                        'request_id' => $req['reasonID'] ?? 0,
                        'from_date' => $req['fromDate'] ?? '',
                        'to_date' => $req['toDate'] ?? '',
                        'reason' => $req['reason'] ?? '',
                        'submitted_date' => $req['submittedDate'] ?? date('Y-m-d'),
                        'status' => $req['status'] ?? 'pending',
                        'duration' => $this->calculateDuration($req['fromDate'] ?? '', $req['toDate'] ?? ''),
                        'acknowledged_by' => $req['acknowledgedBy'] ?? null,
                        'acknowledged_date' => $req['acknowledgedDate'] ?? null,
                    ];
                }
            }
        }

        // Pass data to view
        $data = ['recentRequests' => $formattedRequests];
        $this->view('parent/parentRequests', $data);
    }

    private function calculateDuration($fromDate, $toDate)
    {
        if (empty($fromDate) || empty($toDate)) {
            return 1;
        }

        $from = new DateTime($fromDate);
        $to = new DateTime($toDate);
        $diff = $from->diff($to);

        return $diff->days + 1; // +1 to include both start and end dates
    }
}
