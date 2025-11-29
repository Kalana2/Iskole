<?php

class ReportController extends Controller
{
    // Show page + list
    public function index()
    {
        /** @var ReportModel $model */
        $model = $this->model('ReportModel'); // same helper as in AddNewUserController

        $behaviorReports = $model->getAllReports();

        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $this->view('templates/report', [
            'behaviorReports' => $behaviorReports,
            'flash'           => $flash,
        ]);
    }

    // Handle form submit
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?url=report');
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
            header('Location: /index.php?url=report');
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
                // 'report_date' => null, // optional – DB will use NOW()
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

        header('Location: /index.php?url=report');
        exit;
    }
}
