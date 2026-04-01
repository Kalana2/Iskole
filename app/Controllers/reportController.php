<?php

class ReportController extends Controller
{
    private function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function json(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    private function teacherUserId(): int
    {
        return (int)($_SESSION['userID']
            ?? $_SESSION['userId']
            ?? $_SESSION['user_id']
            ?? 0);
    }

    public function index()
{
    $tab = $_GET['tab'] ?? 'Dashboard';

    $behaviorReports = [];
    $student = null;
    $flash = $_SESSION['report_msg'] ?? null;
    unset($_SESSION['report_msg']);

    $editReport = $_SESSION['edit_report'] ?? null;

    $q = trim($_GET['q'] ?? '');

    if ($tab === 'Reports') {
        /** @var ReportModel $reportModel */
        $reportModel = $this->model('ReportModel');

        $teacherClassId = $_SESSION['classID'] ?? null;

        // ✅ IMPORTANT: do NOT show anything until student searched
        if ($teacherClassId && $q !== '') {
            $student = $reportModel->findStudentInClass((int)$teacherClassId, $q);

            if ($student) {
                // ✅ ONLY that student reports
                $behaviorReports = $reportModel->getReportsByStudent((int)$student['studentID']);
            } else {
                $flash = ['type' => 'error', 'text' => 'Student not found in your class.'];
            }
        }
    }

    $this->view('teacher/index', [
        'tab'             => $tab,
        'behaviorReports' => $behaviorReports,
        'student'         => $student,
        'flash'           => $flash,
        'editReport'      => $editReport,
    ]);
}

public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    $teacherUserId = $this->teacherUserId();
    if ($teacherUserId <= 0) {
        if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Teacher not logged in.']);
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Teacher not logged in.'];
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    $studentID = (int)($_POST['studentID'] ?? 0);
    $reportType  = $_POST['report_type'] ?? '';
    $category    = trim($_POST['category'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($studentID <= 0 || $reportType === '' || $category === '' || $title === '' || $description === '') {
        if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'All fields are required.']);
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'All fields are required.'];
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    /** @var ReportModel $model */
    $model = $this->model('ReportModel');

    $newId = $model->createReport([
        'studentID'   => $studentID,
        'teacherID'   => $teacherUserId,
        'report_type' => $reportType,
        'category'    => $category,
        'title'       => $title,
        'description' => $description,
    ]);

    if (!$newId) {
        if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Insert failed']);
        $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Insert failed.'];
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    // ✅ return JSON for AJAX (fix "response not JSON")
    if ($this->isAjax()) {
        $created = $model->getReportByIdWithTeacherName((int)$newId);
        $this->json(['ok' => true, 'report' => $created]);
    }

    $_SESSION['report_msg'] = ['type' => 'success', 'text' => 'Report added successfully.'];
    header('Location: /index.php?url=teacher&tab=Reports&q=' . urlencode((string)$studentID));
    exit;
}

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Invalid request']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        $reportId = (int)($_POST['report_id'] ?? 0);
        $teacherUserId = $this->teacherUserId();

        if ($reportId <= 0 || $teacherUserId <= 0) {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Invalid request']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        /** @var ReportModel $reportModel */
        $reportModel = $this->model('ReportModel');

        $deleted = $reportModel->deleteReportByIdAndTeacher($reportId, $teacherUserId);

        if ($this->isAjax()) {
            $this->json(['ok' => (bool)$deleted, 'message' => $deleted ? 'Deleted' : 'Delete failed']);
        }

        $_SESSION['report_msg'] = $deleted
            ? ['type' => 'success', 'text' => 'Report deleted successfully.']
            : ['type' => 'error', 'text' => 'Delete failed.'];

        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Invalid request']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        $reportId = (int)($_POST['report_id'] ?? 0);
        $teacherUserId = $this->teacherUserId();

        if ($reportId <= 0 || $teacherUserId <= 0) {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Invalid request']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        /** @var ReportModel $reportModel */
        $reportModel = $this->model('ReportModel');

        $report = $reportModel->getReportByIdAndTeacher($reportId, $teacherUserId);
        if (!$report) {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Not allowed']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        if ($this->isAjax()) {
            $this->json(['ok' => true, 'report' => $report]);
        }

        $_SESSION['edit_report'] = $report;
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Invalid request']);
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        $reportId = (int)($_POST['report_id'] ?? 0);
        $teacherUserId = $this->teacherUserId();

        $reportType  = $_POST['report_type'] ?? '';
        $category    = trim($_POST['category'] ?? '');
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($reportId <= 0 || $teacherUserId <= 0 || $reportType === '' || $category === '' || $title === '' || $description === '') {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Missing fields']);
            header('Location: /index.php?url=teacher&tab=Reports');
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

        if (!$ok) {
            if ($this->isAjax()) $this->json(['ok' => false, 'message' => 'Update failed']);
            $_SESSION['report_msg'] = ['type' => 'error', 'text' => 'Update failed.'];
            header('Location: /index.php?url=teacher&tab=Reports');
            exit;
        }

        $updated = $reportModel->getReportByIdAndTeacher($reportId, $teacherUserId);

        if ($this->isAjax()) {
            $this->json(['ok' => true, 'report' => $updated]);
        }

        $_SESSION['report_msg'] = ['type' => 'success', 'text' => 'Report updated successfully.'];
        header('Location: /index.php?url=teacher&tab=Reports');
        exit;
    }
}
