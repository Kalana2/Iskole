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


        $tab = $_GET['tab'] ?? 'Dashboard';

        // ✅ If Behavior tab -> load reports and view parentBehavior
        if ($tab === 'Behavior') {
            $parentUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

            $reportModel = $this->model('ReportModel');
            $behaviorReports = $reportModel->getReportsForParent((int)$parentUserId);

            $this->view('parent/index', [
                'tab' => $tab,
                'behaviorReports' => $behaviorReports
            ]);
            return;
        }

        // ✅ Parent Time Table -> show linked student's timetable
        if ($tab === 'Time Table') {
            $parentUserId = (int) ($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));
            if ($parentUserId <= 0) {
                header('Location: /login');
                exit;
            }

            try {
                $parentModel = $this->model('ParentModel');
                $linkedStudentIds = $parentModel->getLinkedStudentIdsByUserId($parentUserId);

                $requestedStudentId = isset($_GET['studentID']) ? (int) $_GET['studentID'] : 0;
                $studentId = 0;
                if ($requestedStudentId > 0 && in_array($requestedStudentId, $linkedStudentIds, true)) {
                    $studentId = $requestedStudentId;
                } elseif (!empty($linkedStudentIds)) {
                    $studentId = (int) $linkedStudentIds[0];
                }

                if ($studentId <= 0) {
                    $this->view('parent/index', [
                        'tab' => $tab,
                        'tt_error' => 'No student linked to this parent account.',
                    ]);
                    return;
                }

                $ttModel = $this->model('StudentTimeTableModel');
                $ctx = $ttModel->getStudentTimetableContextByStudentId($studentId, null);

                $this->view('parent/index', array_merge(['tab' => $tab], $ctx));
                return;
            } catch (Throwable $e) {
                $this->view('parent/index', [
                    'tab' => $tab,
                    'tt_error' => $e->getMessage(),
                ]);
                return;
            }
        }

        // default
        $this->view('parent/index', ['tab' => $tab]);
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



    public function behavior()
    {
        $parentUserId = $_SESSION['userId'] ?? 0;



        if (!$parentUserId) {
            header('Location: /login');
            exit;
        }

        $reportModel = $this->model('ReportModel');
        $behaviorReports = $reportModel->getReportsForParent((int)$parentUserId);

        $this->view('parent/parentBehavior', [
            'behaviorReports' => $behaviorReports
        ]);
    }
}
