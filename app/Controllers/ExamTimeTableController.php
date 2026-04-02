<?php
require_once __DIR__ . '/../Model/ExamTimeTableModel.php';

class ExamTimeTableController extends Controller
{

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $model = new ExamTimeTableModel();
        $action = $_POST['action'] ?? '';
        $grade = $_POST['grade'] ?? '6';

        // Sanitize grade
        $grade = preg_replace('/[^0-9A-Za-z_-]/', '', $grade);

        if ($action === 'upload') {
            // Handle file upload
            if (!isset($_FILES['exam_image']) || $_FILES['exam_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['exam_tt_msg'] = 'Please select a valid image file.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }

            $file = $_FILES['exam_image'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['exam_tt_msg'] = 'Only image files (JPEG, PNG, GIF, WebP) are allowed.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }

            // Create upload directory if it doesn't exist
            $uploadDir = __DIR__ . '/../../public/assets/exam_timetable_images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'exam_tt_grade_' . $grade . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            $dbPath = '/assets/exam_timetable_images/' . $filename;

            // Delete old file if exists
            $existingEntry = $model->getByGrade($grade);
            if ($existingEntry && isset($existingEntry['file'])) {
                $oldFile = __DIR__ . '/../../public' . $existingEntry['file'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Update or insert into database
                if ($model->exists($grade)) {
                    $success = $model->update($grade, $dbPath);
                } else {
                    $success = $model->create($grade, $dbPath);
                }

                if ($success) {
                    $_SESSION['exam_tt_msg'] = 'Exam timetable uploaded successfully for Grade ' . $grade . '!';
                } else {
                    $_SESSION['exam_tt_msg'] = 'Failed to save timetable to database.';
                    // Clean up uploaded file
                    unlink($uploadPath);
                }
            } else {
                $_SESSION['exam_tt_msg'] = 'Failed to upload file.';
            }

        } elseif ($action === 'toggle') {
            // Handle visibility toggle
            $newVisibility = $_POST['hidden'] ?? '1';
            $newVisibility = $newVisibility === '1' ? 0 : 1; // Flip the value

            if ($model->exists($grade)) {
                $success = $model->toggleVisibility($grade, $newVisibility);
                if ($success) {
                    $status = $newVisibility == 1 ? 'visible' : 'hidden';
                    $_SESSION['exam_tt_msg'] = 'Timetable for Grade ' . $grade . ' is now ' . $status . '.';
                } else {
                    $_SESSION['exam_tt_msg'] = 'Failed to update visibility.';
                }
            } else {
                $_SESSION['exam_tt_msg'] = 'No timetable exists for Grade ' . $grade . ' yet.';
            }
        }

        // Redirect back with grade and tab parameters
        header('Location: /index.php?url=Admin&tab=Exam Time Table&grade=' . $grade);
        exit;
    }
}