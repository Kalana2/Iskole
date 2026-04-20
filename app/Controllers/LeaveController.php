<?php
require_once __DIR__ . '/../Core/Controller.php';

class LeaveController extends Controller
{
    public function index()
    {
        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

        if (!$teacherUserId) {
            header('Location: /login');
            exit;
        }

        $leaveModel = $this->model('LeaveRequestModel');
        $leaveRequests = $leaveModel->getByTeacher((int)$teacherUserId);
        $leaveBalance = $leaveModel->getLeaveBalanceByTeacher((int)$teacherUserId);

        $editLeave = $_SESSION['edit_leave'] ?? null;
        unset($_SESSION['edit_leave']);

        $this->view('teacher/index', [
            'tab' => 'Leave',
            'leaveRequests' => $leaveRequests,
            'leaveBalance' => $leaveBalance,
            'editLeave' => $editLeave,
        ]);
    }

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
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Missing required fields.'
            ];
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        

        if ($dateTo < $dateFrom) {
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Date To must be after Date From.'
            ];
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

            $_SESSION['leave_msg'] = [
                'type' => 'success',
                'text' => 'Leave request submitted successfully.'
            ];
        } catch (Exception $e) {
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Database error: ' . $e->getMessage()
            ];
        }

        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }

    public function edit()
    {
        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

        if (!$teacherUserId) {
            header('Location: /login');
            exit;
        }

        $leaveId = (int)($_GET['leave_id'] ?? 0);

        if ($leaveId <= 0) {
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Invalid leave request.'
            ];
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        $leaveModel = $this->model('LeaveRequestModel');
        $editLeave = $leaveModel->getByIdForTeacher($leaveId, (int)$teacherUserId);

        if (!$editLeave || strtolower($editLeave['status'] ?? '') !== 'pending') {
            $_SESSION['leave_msg'] = [
                'type' => 'error',
                'text' => 'Only pending requests can be edited.'
            ];
            header('Location: /index.php?url=teacher&tab=Leave');
            exit;
        }

        $_SESSION['edit_leave'] = $editLeave;

        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }

public function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }

    $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

    $leaveId   = (int)($_POST['leave_id'] ?? 0);
    $dateFrom  = $_POST['dateFrom'] ?? '';
    $dateTo    = $_POST['dateTo'] ?? '';
    $leaveType = $_POST['leaveType'] ?? '';
    $reason    = trim($_POST['reason'] ?? '');

    if (!$teacherUserId || !$leaveId || !$dateFrom || !$dateTo || !$leaveType) {
        $_SESSION['leave_msg'] = [
            'type' => 'error',
            'text' => 'Missing required fields.'
        ];
        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }


    if ($dateTo < $dateFrom) {
        $_SESSION['leave_msg'] = [
            'type' => 'error',
            'text' => 'Date To cannot be before Date From.'
        ];
        header('Location: /index.php?url=teacher&tab=Leave');
        exit;
    }

    try {
        $model = $this->model('LeaveRequestModel');

        $ok = $model->updateByTeacher($leaveId, (int)$teacherUserId, [
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
            'leaveType' => $leaveType,
            'reason'    => $reason,
        ]);

        $_SESSION['leave_msg'] = $ok
            ? ['type' => 'success', 'text' => 'Leave request updated successfully.']
            : ['type' => 'error', 'text' => 'Only pending requests can be updated.'];
    } catch (Exception $e) {
        $_SESSION['leave_msg'] = [
            'type' => 'error',
            'text' => 'Database error: ' . $e->getMessage()
        ];
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

        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);
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

    public function decide()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?url=mp&tab=Requests');
            exit;
        }

        $leaveId = (int)($_POST['leave_id'] ?? 0);
        $status  = $_POST['status'] ?? '';
        $managerUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

        if (!$leaveId || !in_array($status, ['approved', 'rejected'], true)) {
            header('Location: /index.php?url=mp&tab=Requests');
            exit;
        }

        $leaveModel = $this->model('LeaveRequestModel');
        $leaveModel->decide($leaveId, (int)$managerUserId, $status, null);

        header('Location: /index.php?url=mp&tab=Requests');
        exit;
    }
}
