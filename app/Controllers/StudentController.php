<?php

require_once __DIR__ . '/../Core/Controller.php';

class StudentController extends Controller
{
    public function index()
    {
        $tab = $_GET['tab'] ?? 'Announcements';

        if ($tab === 'Time Table') {
            $userId = (int) ($_SESSION['user_id'] ?? 0);
            $fallbackClassId = isset($_SESSION['classID']) ? (int) $_SESSION['classID'] : (isset($_SESSION['class_id']) ? (int) $_SESSION['class_id'] : null);

            try {
                $model = $this->model('StudentTimeTableModel');
                $ctx = $model->getStudentTimetableContext($userId, $fallbackClassId);
                $this->view('student/index', $ctx);
                return;
            } catch (Throwable $e) {
                // Fall back to rendering without data; template will show placeholders.
                $this->view('student/index', ['tt_error' => $e->getMessage()]);
                return;
            }
        }

        $this->view('student/index');
    }
}