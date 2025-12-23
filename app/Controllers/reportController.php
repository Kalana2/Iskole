<?php

class ReportController extends Controller
{
    public function index()
    {
        $tab = $_GET['tab'] ?? 'Dashboard';

        $behaviorReports = [];
        $student = null; // ✅ NEW
        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        // ✅ NEW: read search query
        $q = trim($_GET['q'] ?? '');

        if ($tab === 'Reports') {
            /** @var ReportModel $reportModel */
            $reportModel = $this->model('ReportModel');

            // your existing reports list
            $behaviorReports = $reportModel->getAllReports();

            // ✅ NEW: teacher class id from session (change key if yours differs)
            $teacherClassId = $_SESSION['classID'] ?? null;

            // ✅ NEW: if user searched, load student details
            if ($teacherClassId && $q !== '') {
                $student = $reportModel->findStudentInClass($teacherClassId, $q);

                // optional: flash if not found
                if (!$student) {
                    $flash = ['type' => 'error', 'text' => 'Student not found in your class.'];
                }
            }
        }

        $this->view('teacher/index', [
            'tab'             => $tab,
            'behaviorReports' => $behaviorReports,
            'student'         => $student, // ✅ NEW: pass to view
            'flash'           => $flash,
        ]);
    }

    // POST /index.php?url=report/submit
    public function submit()
    {
        // 🔴 DEBUG line REMOVE now (use only if needed)
        // die('✅ submit reached. METHOD = ' . $_SERVER['REQUEST_METHOD'] .
        //    ' POST = ' . print_r($_POST, true));
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }


        $studentID = $_POST['studentID'] ?? null;

        if (!$studentID) {
            $_SESSION['report_msg'] = [
                'type' => 'error',
                'text' => 'Please search and select a student before adding a report.'
            ];
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        $reportType  = $_POST['report_type'] ?? null;
        $category    = trim($_POST['category'] ?? '');
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$reportType || !$category || !$title || !$description) {
            $_SESSION['report_msg'] = [
                'type' => 'error',
                'text' => 'Missing required fields.',
            ];
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        try {
            /** @var ReportModel $model */
            $model = $this->model('ReportModel');

            $model->createReport([
                'studentID'   => (int)$studentID,
                'report_type' => $reportType,
                'category'    => $category,
                'title'       => $title,
                'description' => $description,
            ]);

            $_SESSION['report_msg'] = [
                'type' => 'success',
                'text' => 'Report added successfully.',
            ];
        } catch (Exception $e) {
            $_SESSION['report_msg'] = [
                'type' => 'error',
                'text' => 'Database error: ' . $e->getMessage(),
            ];
        }

        // ✅ always go back to the styled teacher page
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }
}
