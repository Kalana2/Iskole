<?php
require_once '../app/Core/UserController.php';

class Mpcontroller extends UserController
{

    public function index()
    {
        // Handle announcement actions
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }

        // add tab handling
        $tab = $_GET['tab'] ?? 'Announcements';

        // default data array
        $data = [
            'tab' => $tab
        ];

        // Requests tab → load pending leave requests
        if ($tab === 'Requests') {
            $leaveModel = $this->model('LeaveRequestModel');
            $data['pendingLeaves'] = $leaveModel->getPending();
        }

        // Assign Class Teacher tab → load classes and teachers
        if ($tab === 'Assign Class Teacher') {
            $ctModel = $this->model('ClassTeacherModel');
            $data['classesWithTeachers'] = $ctModel->getAllClassesWithTeachers();
            $data['teachers'] = $ctModel->getAllTeachers();
            $data['flash'] = $_SESSION['ct_msg'] ?? null;
            unset($_SESSION['ct_msg']);
        }

        // Relief tab → load pending relief slots and stats
        if ($tab === 'Relief') {
            require_once __DIR__ . '/../Model/reliefModel.php';
            $reliefModel = new reliefModel();
            $date = $_GET['date'] ?? date('Y-m-d');
            $data['pendingRelief'] = $reliefModel->getPendingReliefSlots($date);
            $data['presentTeacherCount'] = $reliefModel->getPresentTeacherCount($date);
            $data['selectedDate'] = $date;
        }

        // pass $data to view
        $this->view('mp/index', $data);
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
