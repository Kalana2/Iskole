<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Controller.php';

class ReportController extends Controller
{
    public function index()
    {
        $pdo = Database::getInstance();

        // 1) POST: insert new report
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            die('ReportController POST reached: ' . print_r($_POST, true));

            $reportType  = $_POST['report_type'] ?? null;
            $category    = trim($_POST['category'] ?? '');
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // 🔍 DEBUG: check if POST even comes here (uncomment once):
            // die('ReportController POST reached: ' . print_r($_POST, true));

            if (!$reportType || !$category || !$title || !$description) {
                header('Location: /index.php?url=report&error=' . urlencode('Missing required fields'));
                exit;
            }

            try {
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

                header('Location: /index.php?url=report&success=1');
                exit;
            } catch (Exception $e) {
                header('Location: /index.php?url=report&error=' . urlencode($e->getMessage()));
                exit;
            }
        }

        // 2) GET: load reports to show in view
        $stmt = $pdo->query("SELECT * FROM report ORDER BY report_date DESC");
        $behaviorReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('templates/report', [
            'behaviorReports' => $behaviorReports,
        ]);
    }
}
