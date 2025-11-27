<?php
require_once __DIR__ . '/../../Core/Controller.php';
require_once __DIR__ . '/../../Model/materialModel.php';

class MaterialController extends Controller
{
    public function index()
    {
        $this->view('teacher/uploadMaterials');
    }

    public function create()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            header('Content-Type: application/json; charset=utf-8');
            try {
                // Debug logging
                error_log("Upload started for user: " . ($_SESSION['user_id'] ?? 'not set'));

                // Check if session is available  
                if (!isset($_SESSION['user_id'])) {
                    echo json_encode(['success' => false, 'message' => 'Session not found. Please login again.']);
                    exit;
                }

                $model = new Material();

                // Read regular inputs
                $grade     = $_POST['grade'] ?? null;
                $class     = $_POST['class'] ?? null;
                $subject   = $_POST['subject'] ?? null;
                $title     = $_POST['material-title'] ?? null;
                $desc      = $_POST['material-description'] ?? null;

                // Debug logging
                error_log("Form data - Grade: $grade, Class: $class, Subject: $subject, Title: $title");

                // Validate required fields
                if (!$grade || !$class || !$subject || !$title || !$desc) {
                    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
                    exit;
                }

                // Read file input
                if (!empty($_FILES['file-upload']['name'])) {
                    $file = $_FILES['file-upload'];
                    $fileError = $file['error'];

                    error_log("File upload - Name: " . $file['name'] . ", Size: " . $file['size'] . ", Error: " . $fileError);

                    if ($fileError !== 0) {
                        $errorMessages = [
                            UPLOAD_ERR_INI_SIZE => 'File size exceeds upload_max_filesize.',
                            UPLOAD_ERR_FORM_SIZE => 'File size exceeds MAX_FILE_SIZE.',
                            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
                        ];
                        $errorMessage = $errorMessages[$fileError] ?? "Unknown upload error: $fileError";
                        echo json_encode(['success' => false, 'message' => $errorMessage]);
                        exit;
                    }

                    // Validate file size (10MB limit)
                    if ($file['size'] > 10 * 1024 * 1024) {
                        echo json_encode(['success' => false, 'message' => 'File size must be less than 10MB.']);
                        exit;
                    }

                    // Validate file type
                    $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];
                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!in_array($fileExtension, $allowedTypes)) {
                        echo json_encode(['success' => false, 'message' => 'File type not allowed. Only PDF, DOC, DOCX, PPT, PPTX, and image files are allowed.']);
                        exit;
                    }

                    // Create unique filename to prevent conflicts
                    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
                    $uniqueFilename = $originalName . '_' . time() . '_' . uniqid() . '.' . $fileExtension;

                    // Define upload directory
                    $uploadDir = __DIR__ . '/../../../public/uploads/materials/';
                    $uploadPath = $uploadDir . $uniqueFilename;

                    // Ensure upload directory exists
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
                            exit;
                        }
                    }

                    // Move uploaded file
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        echo json_encode(['success' => false, 'message' => 'Failed to upload file to server.']);
                        exit;
                    }

                    // Store relative path in database
                    $relativePath = '/uploads/materials/' . $uniqueFilename;

                    if ($model->addMaterial($grade, $class, $subject, $title, $desc, $relativePath)) {
                        echo json_encode(['success' => true, 'message' => 'Material uploaded successfully!']);
                    } else {
                        // If database save fails, remove the uploaded file
                        if (file_exists($uploadPath)) {
                            unlink($uploadPath);
                        }
                        echo json_encode(['success' => false, 'message' => 'Failed to save material to database.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
                }
            } catch (Exception $e) {
                error_log('Material upload error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            }
            exit;
        }
    }
}
