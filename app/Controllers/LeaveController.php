<?php
require_once __DIR__ . '/../Core/Controller.php';

class LeaveController extends Controller
{
    // GET: show form
    public function index()
    {
        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);
        if (!$teacherUserId) {
            header('Location: /login');
            exit;
        }

        $leaveModel = $this->model('LeaveRequestModel');

        // ✅ teacherගේ leave requests DB එකෙන්
        $leaveRequests = $leaveModel->getByTeacher((int)$teacherUserId);

        // ✅ teacher tab view එකට pass කරනවා
        $this->view('teacher/index', [
            'tab' => 'Leave',
            'leaveRequests' => $leaveRequests
        ]);
    }


    // POST: submit leave request
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

        $dateFrom  = $_POST['dateFrom'] ?? '';
        $dateTo    = $_POST['dateTo'] ?? '';
        $leaveType = $_POST['leaveType'] ?? '';
        $reason    = trim($_POST['reason'] ?? '');

        if (!$teacherUserId || !$dateFrom || !$dateTo || !$leaveType) {
            $_SESSION['leave_msg'] = ['type' => 'error', 'text' => 'Missing required fields.'];
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        try {
            $model = $this->model('LeaveRequestModel');

            $model->create([
                'teacherUserID' => (int)$teacherUserId,
                'dateFrom'      => $dateFrom,
                'dateTo'        => $dateTo,
                'leaveType'     => $leaveType,
                'reason'        => $reason,
            ]);

            $_SESSION['leave_msg'] = ['type' => 'success', 'text' => 'Leave request submitted successfully.'];
        } catch (Exception $e) {
            $_SESSION['leave_msg'] = ['type' => 'error', 'text' => 'Database error: ' . $e->getMessage()];
        }

        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }



    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        $teacherUserId = $_SESSION['userId'] ?? 0;
        $leaveId = (int)($_POST['leave_id'] ?? 0);

        $leaveModel = $this->model('LeaveRequestModel');

        if ($leaveModel->cancel($leaveId, $teacherUserId)) {
            $_SESSION['leave_msg'] = [
                'type' => 'success',
                'text' => 'Leave request cancelled.'
            ];
        } else {
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Only pending requests can be cancelled.'
            ];
        }

        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }
}
