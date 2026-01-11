<?php
require_once __DIR__ . '/../Core/Controller.php';

class TeacherController extends Controller
{
    public function index()
    {
        // Handle announcement actions (✅ DO NOT CHANGE)
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }

        // ✅ NEW: handle tabs + reports search
        $tab = $_GET['tab'] ?? 'Dashboard';

        $behaviorReports = [];
        $student = null;
        $suggestions = [];

        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $q = trim($_GET['q'] ?? '');

        if ($tab === 'Reports') {

            $reportModel = $this->model('ReportModel');

            // Behavior reports
            $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);
            $behaviorReports = $reportModel->getReportsByTeacher((int)$teacherUserId);

            // Models
            $teacherModel = $this->model('TeacherModel');
            $studentModel = $this->model('StudentModel');

            // ✅ teacher classID
            $teacher = $teacherModel->getTeacherByUserID($teacherUserId);
            $teacherClassId = (int)($teacher['classID'] ?? 0);

            // ✅ dropdown suggestions (ONLY this class)
            $suggestions = [];
            if ($teacherClassId > 0) {
                $suggestions = $studentModel->getStudentsByClassId($teacherClassId);
            }

            // 🔍 search result (still class-based)
            if ($teacherClassId > 0 && $q !== '') {
                $student = $this->findStudentInClass($teacherClassId, $q);

                if (!$student) {
                    $flash = [
                        'type' => 'error',
                        'text' => 'Student not found in your class.'
                    ];
                }
            }
        }



        // ✅ IMPORTANT: pass data to the view
        $this->view('teacher/index', [
            'tab' => $tab,
            'behaviorReports' => $behaviorReports,
            'student' => $student,
            'flash' => $flash,
            'suggestions' => $suggestions, // ✅ ADD THIS
        ]);
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


    public function findStudentInClass($classId, $query)
    {
        $model = $this->model('UserModel');
        return $model->findStudentInClass((int)$classId, $query);
    }
}
