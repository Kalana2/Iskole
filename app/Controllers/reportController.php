<?php

class ReportController extends Controller
{
    public function index()
    {
        /** @var ReportModel $model */
        $model = $this->model('ReportModel');
        $behaviorReports = $model->getAllReports();

        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $this->view('templates/report', [
            'behaviorReports' => $behaviorReports,
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
            header('Location: /teacher?tab=Reports');
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
            header('Location: /teacher?tab=Reports');
            exit;
        }

        try {
            /** @var ReportModel $model */
            $model = $this->model('ReportModel');

            $model->createReport([
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
        header('Location: /teacher?tab=Reports');
        exit;
    }
}
