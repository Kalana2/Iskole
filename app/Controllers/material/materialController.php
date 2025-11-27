<?php
require_once __DIR__ . '/../../Core/Controller.php';
require_once __DIR__ . '/../../Model/materialModel.php';
require_once __DIR__ . '/../../Model/studentModel.php';

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

    public function update()
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
                $materialID = $_POST['materialID'] ?? null;

                // Validate material ID
                if (!$materialID) {
                    echo json_encode(['success' => false, 'message' => 'Material ID is required.']);
                    exit;
                }

                $grade     = $_POST['grade'] ?? null;
                $class     = $_POST['class'] ?? null;
                $subject   = $_POST['subject'] ?? null;
                $title     = $_POST['title'] ?? null;
                $desc      = $_POST['description'] ?? null;

                // Debug logging
                error_log("Form data - Grade: $grade, Class: $class, Subject: $subject, Title: $title");

                // Validate required fields
                if (!$grade || !$class || !$subject || !$title || !$desc) {
                    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
                    exit;
                }

                // Read file input (optional for updates)
                $relativePath = null; // Default to no file update
                if (!empty($_FILES['file']['name'])) {
                    $file = $_FILES['file'];
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
                }

                // Update material (with or without new file)
                if ($relativePath) {
                    // Update with new file
                    $success = $model->editMaterial($materialID, $grade, $class, $subject, $title, $desc, $relativePath);
                } else {
                    // Update without changing file
                    $success = $model->editMaterialWithoutFile($materialID, $grade, $class, $subject, $title, $desc);
                }

                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Material updated successfully!']);
                } else {
                    // If database save fails and we uploaded a new file, remove it
                    if ($relativePath && isset($uploadPath) && file_exists($uploadPath)) {
                        unlink($uploadPath);
                    }
                    echo json_encode(['success' => false, 'message' => 'Failed to update material in database.']);
                }
            } catch (Exception $e) {
                error_log('Material upload error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            }
            exit;
        }
    }

    public function studentView()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            error_log('Student view: No user session found');
            return [];
        }

        $model = new Material();
        $student = new StudentModel();

        try {
            $result = $student->getStudentGradeClass($_SESSION['user_id']);
        } catch (PDOException $e) {
            error_log('Error fetching student grade and class: ' . $e->getMessage());
            return [];
        }

        $grade = $result['grade'] ?? null;
        $class = $result['class'] ?? null;

        if ($grade == null || $class == null) {
            error_log('Student view: Grade or class is empty for user ID: ' . $_SESSION['user_id']);
            return [];
        }

        try {
            $materials = $model->getMaterial($grade, $class);
            return $materials;
        } catch (Exception $e) {
            error_log('Error fetching materials: ' . $e->getMessage());
            return [];
        }
    }

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materialID'])) {
            try {
                $materialID = $_POST['materialID'];
                $model = new Material();

                // Get the file path from the database
                $fileData = $model->fetchFileName($materialID);

                if (!$fileData || !isset($fileData['file'])) {
                    http_response_code(404);
                    exit('File not found in database.');
                }

                $filePath = $fileData['file'];
                $fullPath = __DIR__ . '/../../../public' . $filePath;

                // Check if file exists
                if (!file_exists($fullPath)) {
                    http_response_code(404);
                    exit('File not found on server: ' . $fullPath);
                }

                // Get file info
                $fileName = basename($fullPath);
                $fileSize = filesize($fullPath);
                $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

                // Clear any output buffering
                if (ob_get_level()) {
                    ob_end_clean();
                }

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
                exit('Server error: ' . $e->getMessage());
            }
        } else {
            http_response_code(400);
            exit('Invalid request. Missing material ID.');
        }
    }
}
