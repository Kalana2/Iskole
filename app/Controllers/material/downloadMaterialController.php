<?php
require_once __DIR__ . '/../../Core/Controller.php';
require_once __DIR__ . '/../../Model/materialModel.php';

class DownloadMaterialController extends Controller
{
    public function download()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit('Access denied. Please log in.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download']) && isset($_POST['materialID'])) {
            try {
                $materialID = $_POST['materialID'];
                $model = new Material();

                // Get the file path from the database
                $fileData = $model->fetchFileName($materialID);

                if (!$fileData || !isset($fileData['file'])) {
                    http_response_code(404);
                    exit('File not found.');
                }

                $filePath = $fileData['file'];
                $fullPath = __DIR__ . '/../../../public' . $filePath;

                // Check if file exists
                if (!file_exists($fullPath)) {
                    http_response_code(404);
                    exit('File not found on server.');
                }

                // Get file info
                $fileName = basename($fullPath);
                $fileSize = filesize($fullPath);
                $mimeType = mime_content_type($fullPath);

                // Set headers for download
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Content-Length: ' . $fileSize);
                header('Cache-Control: no-cache, must-revalidate');
                header('Pragma: public');

                // Output file
                readfile($fullPath);
                exit;
            } catch (Exception $e) {
                error_log('Material download error: ' . $e->getMessage());
                http_response_code(500);
                exit('Server error occurred while downloading file.');
            }
        } else {
            http_response_code(400);
            exit('Invalid request.');
        }
    }
}

// Handle the request if this file is accessed directly
if (isset($_POST['download'])) {
    $controller = new DownloadMaterialController();
    $controller->download();
}
