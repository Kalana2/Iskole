<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Model/TeacherModel.php';
require_once __DIR__ . '/../Model/reliefModel.php';

class TeacherController extends Controller
{
    public function index()
    {
        // Handle announcement actions
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }

        $this->view('teacher/index');
    }

    public function materials()
    {
        // Handle material upload actions
        if (isset($_GET['action']) && $_GET['action'] === 'create') {
            require_once __DIR__ . '/MaterialController.php';
            $materialController = new MaterialController();
            $materialController->create();
            return;
        }

        // Handle material hide action
        if (isset($_GET['action']) && $_GET['action'] === 'hide') {
            include_once __DIR__ . '/material/hideMaterialController.php';
            return;
        }

        // Handle material unhide action
        if (isset($_GET['action']) && $_GET['action'] === 'unhide') {
            include_once __DIR__ . '/material/unhideMaterialController.php';
            return;
        }

        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            include_once __DIR__ . '/material/deleteMaterialController.php';
            return;
        }

        if (isset($_GET['action']) && $_GET['action'] === 'update') {
            require_once __DIR__ . '/material/materialController.php';
            $materialController = new MaterialController();
            $materialController->update();
            return;
        }

        $this->view('teacher/materials');
    }

    public function relief()
    {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = date('Y-m-d');
        }

        $userId = (int)($_SESSION['user_id'] ?? 0);
        $error = null;
        $reliefPeriods = [];

        try {
            $teacherModel = new TeacherModel();
            $teacherId = $teacherModel->getTeacherIDByUserID($userId);

            if (!$teacherId) {
                $error = 'Teacher profile not found for this account.';
            } else {
                // Helpful for existing attendance APIs that check this session key.
                $_SESSION['teacher_id'] = (int)$teacherId;

                $reliefModel = new reliefModel();
                $reliefPeriods = $reliefModel->getReliefAssignmentsForTeacher((int)$teacherId, $selectedDate);
            }
        } catch (Exception $e) {
            error_log('Teacher relief load error: ' . $e->getMessage());
            $error = 'Failed to load relief periods.';
        }

        $this->view('teacher/relief', [
            'selectedDate' => $selectedDate,
            'reliefPeriods' => $reliefPeriods,
            'error' => $error,
        ]);
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
