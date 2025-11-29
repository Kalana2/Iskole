<?php

class ReportController extends Controller
{
    // GET /report  -> show page + list
    public function index()
    {
        $pdo = Database::getInstance();

        // DB එකෙන් behavior reports ගන්න
        $stmt = $pdo->query("SELECT * FROM report ORDER BY report_date DESC");
        $behaviorReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $flash = $_SESSION['report_msg'] ?? null;
        unset($_SESSION['report_msg']);

        $this->view('templates/report', [
            'behaviorReports' => $behaviorReports,
            'flash'           => $flash,
        ]);
    }

    // POST /report/submit  -> insert new behavior report
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /report');
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
            header('Location: /report');
            exit;
        }

        try {
            $pdo = Database::getInstance();

            $stmt = $pdo->prepare(
                "INSERT INTO report (report_type, category, title, description, report_date)
                 VALUES (:type, :category, :title, :description, NOW())"
            );

            $stmt->execute([
                ':type'        => $reportType,
                ':category'    => $category,
                ':title'       => $title,
                ':description' => $description,
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

        header('Location: /report');
        exit;
    }
}
