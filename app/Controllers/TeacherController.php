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



        // 2) Flash message (ReportController::submit එකෙන් ආ message)
        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        // 3) Default value
        $behaviorReports = [];

        // 4) මේ page එක Reports tab එකෙන් විවෘත කරද්දි විතරක් DB එකෙන් reports ගන්න
        if (isset($_GET['tab']) && $_GET['tab'] === 'Reports') {
            /** @var ReportModel $reportModel */
            $reportModel = $this->model('ReportModel');

            // දැන්ට නම් DB එකෙන් සියලු reports ගන්නවා
            // (පස්සේ student/teacher අනුව filter කරන්න පුළුවන්)
            $behaviorReports = $reportModel->getAllReports();
        }

        // 5) data එක්ක view එක load කරන්න
        $this->view('teacher/index', [
            'behaviorReports' => $behaviorReports,
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
