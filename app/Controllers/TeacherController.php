<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Model/TeacherModel.php';
require_once __DIR__ . '/../Model/reliefModel.php';

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

        // // Handle announcement actions (✅ DO NOT CHANGE)
        // if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
        //     $this->handleAnnouncementAction($_GET['action']);
        //     return;
        // }

        // ✅ NEW: handle tabs + reports search

        $behaviorReports = [];
        $student = null;
        $leaveRequests = [];
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

        if ($tab === 'Leave') {
            $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);

            $leaveModel = $this->model('LeaveRequestModel');
            $leaveRequests = $leaveModel->getByTeacher((int)$teacherUserId);
        }


        // ✅ IMPORTANT: pass data to the view
        $this->view('teacher/index', [
            'tab' => $tab,
            'behaviorReports' => $behaviorReports,
            'student' => $student,
            'flash' => $flash,
            'leaveRequests' => $leaveRequests,
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
            $teacherModel = $this->model('TeacherModel');
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


    public function findStudentInClass($classId, $query)
    {
        $model = $this->model('UserModel');
        return $model->findStudentInClass((int)$classId, $query);
    }
}
