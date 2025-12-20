<?php
require_once __DIR__ . '/../Core/Controller.php';

class TeacherController extends Controller
{
    public function index()
    {
        // Handle announcement actions
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }



        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $behaviorReports = [];
        $student = null;                // ✅ NEW
        $tab = $_GET['tab'] ?? 'Dashboard'; // ✅ NEW (keep tab)

        if ($tab === 'Reports') {


            error_log("Teacher session classID = " . print_r($_SESSION['classID'] ?? null, true));
            error_log("Search q = " . print_r($_GET['q'] ?? null, true));


            /** @var ReportModel $reportModel */
            $reportModel = $this->model('ReportModel');

            $behaviorReports = $reportModel->getAllReports();

            // ✅ NEW: search query
            $q = trim($_GET['q'] ?? '');

            // ✅ NEW: teacher class id from session (change key if your project uses different name)
            $teacherClassId = $_SESSION['classID'] ?? null;

            if ($teacherClassId && $q !== '') {
                $student = $reportModel->findStudentInClass((int)$teacherClassId, $q);

                if (!$student) {
                    $flash = ['type' => 'error', 'text' => 'Student not found in your class.'];
                }
            }
        }

        $this->view('teacher/index', [
            'tab'             => $tab,            // ✅ NEW
            'behaviorReports' => $behaviorReports,
            'student'         => $student,        // ✅ NEW
            'flash'           => $flash,
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
}
