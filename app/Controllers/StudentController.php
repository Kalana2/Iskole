<?php

require_once __DIR__ . '/../Core/Controller.php';

class StudentController extends Controller
{
    public function index()
    {
        $tab = $_GET['tab'] ?? 'Announcements';

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $fallbackClassId = isset($_SESSION['classID']) ? (int) $_SESSION['classID'] : (isset($_SESSION['class_id']) ? (int) $_SESSION['class_id'] : null);

        $baseCtx = [];
        if ($userId > 0) {
            try {
                $ttModel = $this->model('StudentTimeTableModel');
                $baseCtx['studentInfo'] = $ttModel->getStudentInfoForUserId($userId, $fallbackClassId);
            } catch (Throwable $e) {
                // Ignore; templates will show placeholders.
            }
        }

        if ($tab === 'Time Table') {
            try {
                $model = $this->model('StudentTimeTableModel');
                $ctx = $model->getStudentTimetableContext($userId, $fallbackClassId);
                $this->view('student/index', $ctx);
                return;
            } catch (Throwable $e) {
                // Fall back to rendering without data; template will show placeholders.
                $this->view('student/index', array_merge($baseCtx, ['tt_error' => $e->getMessage()]));
                return;
            }
        }

        $this->view('student/index', $baseCtx);
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
}
