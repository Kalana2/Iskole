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

        $behaviorReports = [];
        $student = null;
        $leaveRequests = [];
        $suggestions = [];

        $editReport = $_SESSION['edit_report'] ?? null;
        unset($_SESSION['edit_report']);

        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $q = trim($_GET['q'] ?? '');

        if ($tab === 'Reports') {

            /** @var ReportModel $reportModel */
            $reportModel = $this->model('ReportModel');

            // ✅ teacher user id (keep consistent)
            $teacherUserId = (int)($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));

            // Behavior reports (only this teacher)
            $behaviorReports = $reportModel->getReportsByTeacher($teacherUserId);

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

            // 🔍 search result (STRICT class-based)
            if ($teacherClassId > 0 && $q !== '') {

                // ✅ FIX: use ReportModel->findStudentInClass() (this has correct SQL with classID filter)
                $student = $reportModel->findStudentInClass($teacherClassId, $q);

                if (!$student) {
                    $flash = [
                        'type' => 'error',
                        'text' => 'Student not found in your class.'
                    ];
                }
            }

			// Performance stats (overall average + ranks)
			$performance = null;
			$availableTerms = [];
			$selectedTerm = null;
			if ($student && !empty($student['studentID'])) {
				$marksModel = $this->model('MarksReportModel');
				$selectedTerm = isset($_GET['term']) ? trim((string)$_GET['term']) : null;
				$availableTerms = $marksModel->getAvailableTermsForStudent((int)$student['studentID']);
				$performance = $marksModel->getStudentPerformanceStats((int)$student['studentID'], $selectedTerm);
				$selectedTerm = $performance['term'] ?? $selectedTerm;
			}
        }

        if ($tab === 'Leave') {
            $teacherUserId = (int)($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));

            $leaveModel = $this->model('LeaveRequestModel');
            $leaveRequests = $leaveModel->getByTeacher($teacherUserId);
        }

        // ✅ IMPORTANT: pass data to the view
        $this->view('teacher/index', [
            'tab' => $tab,
            'behaviorReports' => $behaviorReports,
            'student' => $student,
            'flash' => $flash,
            'leaveRequests' => $leaveRequests,
            'suggestions' => $suggestions,
            'editReport' => $editReport,
        ]);
    }

    public function materials()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'create') {
            require_once __DIR__ . '/MaterialController.php';
            $materialController = new MaterialController();
            $materialController->create();
            return;
        }

        if (isset($_GET['action']) && $_GET['action'] === 'hide') {
            include_once __DIR__ . '/material/hideMaterialController.php';
            return;
        }

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
