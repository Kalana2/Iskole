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




        $teacherUserId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? null);

        if (!$teacherUserId) {
            $_SESSION['report_msg'] = [
                'type' => 'error',
                'text' => 'Teacher not logged in.'
            ];
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
                'teacherID'   => (int)$teacherUserId,
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



    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /index.php?url=teacher&tab=Reports");
            exit;
        }

        $reportId = (int)($_POST['report_id'] ?? 0);
        $teacherUserId = (int)($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));

        if ($reportId <= 0) {
            $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Invalid report ID.'];
            header("Location: /index.php?url=teacher&tab=Reports");
            exit;
        }

        $reportModel = $this->model('ReportModel');

        // ✅ Security: teacher ට අයිති report එකක්ද කියලා check කරලා delete කරන්න
        $deleted = $reportModel->deleteReportByIdAndTeacher($reportId, $teacherUserId);

        if ($deleted) {
            $_SESSION['report_msg'] = ['type' => 'success', 'text' => 'Report deleted successfully.'];
        } else {
            $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Delete failed (not found or not allowed).'];
        }

        // search q එක තියෙනව නම් ආපහු ඒකත් preserve කරලා redirect කරන්න
        $q = trim($_POST['q'] ?? '');
        $redirect = "/index.php?url=teacher&tab=Reports";
        if ($q !== '') $redirect .= "&q=" . urlencode($q);

        header("Location: $redirect");
        exit;
    }




    public function edit()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

    $reportId = (int)($_POST['report_id'] ?? 0);
    $teacherUserId = (int)($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));

    if ($reportId <= 0 || $teacherUserId <= 0) {
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Invalid edit request.'];
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

    /** @var ReportModel $reportModel */
    $reportModel = $this->model('ReportModel');

    // ✅ security: teacher owns this report
    $report = $reportModel->getReportByIdAndTeacher($reportId, $teacherUserId);

    if (!$report) {
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Report not found or not allowed.'];
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

  
    $_SESSION['edit_report'] = $report;

    // preserve q
    $q = trim($_POST['q'] ?? '');
    $redirect = "/index.php?url=teacher&tab=Reports";
    if ($q !== '') $redirect .= "&q=" . urlencode($q);

    header("Location: $redirect");
    exit;
}


public function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

    $reportId = (int)($_POST['report_id'] ?? 0);
    $teacherUserId = (int)($_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0));

    if ($reportId <= 0 || $teacherUserId <= 0) {
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Invalid update request.'];
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

    $reportType  = $_POST['report_type'] ?? '';
    $category    = trim($_POST['category'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($reportType === '' || $category === '' || $title === '' || $description === '') {
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Missing required fields.'];
        header("Location: /index.php?url=teacher&tab=Reports");
        exit;
    }

    /** @var ReportModel $reportModel */
    $reportModel = $this->model('ReportModel');


    $ok = $reportModel->updateReportByTeacher($reportId, $teacherUserId, [
        'report_type' => $reportType,
        'category'    => $category,
        'title'       => $title,
        'description' => $description,
    ]);

    if ($ok) {
        $_SESSION['report_msg'] = ['type' => 'success', 'text' => 'Report updated successfully.'];
        unset($_SESSION['edit_report']); // ✅ stop edit mode
    } else {
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Update failed (not found or not allowed).'];
    }

    // preserve q
    $q = trim($_POST['q'] ?? '');
    $redirect = "/index.php?url=teacher&tab=Reports";
    if ($q !== '') $redirect .= "&q=" . urlencode($q);

    header("Location: $redirect");
    exit;
}

}
