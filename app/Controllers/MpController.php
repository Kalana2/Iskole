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

        // ✅ add tab handling
        $tab = $_GET['tab'] ?? 'Announcements';

        // ✅ default data array
        $data = [
            'tab' => $tab
        ];

        // ✅ Requests tab → load pending leave requests
        if ($tab === 'Requests') {
            $leaveModel = $this->model('LeaveRequestModel');
            $data['pendingLeaves'] = $leaveModel->getPending();
        }

        // ✅ pass $data to view
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

    public function createMp($data)
    {
        try {
            $this->userModel = $this->model('MpModel');
            $this->userModel->createMp($data);
            // $this->view('mp/success', ['message' => 'MP created successfully.']);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request to create MP: " . $e->getMessage());
        }
    }
}
