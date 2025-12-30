<?php
require_once __DIR__ . '/../Core/Controller.php';

class TeacherController extends Controller
{
    public function index()
    {
        $tab = $_GET['tab'] ?? 'Announcements';

        if ($tab === 'Time Table') {
            $userId = (int) ($_SESSION['user_id'] ?? 0);

            try {
                $model = $this->model('TeacherTimeTableModel');
                $ctx = $model->getTeacherTimetableContext($userId);
                $this->view('teacher/index', $ctx);
                return;
            } catch (Throwable $e) {
                $this->view('teacher/index', ['tt_error' => $e->getMessage()]);
                return;
            }
        }

        $this->view('teacher/index');
    }
}
