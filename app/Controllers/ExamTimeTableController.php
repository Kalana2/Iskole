<?php
// app/Controllers/ExamTimeTableController.php
session_start();
require_once __DIR__ . '/../Model/ExamTimeTableModel.php';

require_once __DIR__ . '/../Core/Database.php';

class ExamTimeTableController
{
    private $model;
    private $uploadDir; // web path (public)
    private $uploadFsDir; // filesystem absolute

    public function __construct()
    {
        $this->model = new ExamTimeTableModel();
        $this->uploadDir = '/uploads/exam_timetables/'; // web-accessible path
        $this->uploadFsDir = $_SERVER['DOCUMENT_ROOT'] . $this->uploadDir;

        if (!is_dir($this->uploadFsDir)) {
            mkdir($this->uploadFsDir, 0777, true);
        }
    }



    public function upload()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['exam_tt_msg'] = "Invalid request method.";
            header("Location: /admin/dashboard?tab=Exam Time Table");
            exit;
        }

        $grade = $_POST['grade'] ?? null;
        if (!$grade) {
            $_SESSION['exam_tt_msg'] = "Grade is required.";
            header("Location: /admin/dashboard?tab=Exam Time Table");
            exit;
        }

        if (!isset($_FILES['exam_image']) || $_FILES['exam_image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['exam_tt_msg'] = "Upload failed: file missing or error.";
            header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
            exit;
        }

        $file = $_FILES['exam_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['exam_tt_msg'] = "Invalid image format. Allowed: jpg,jpeg,png,webp.";
            header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
            exit;
        }

        // Sanitize grade (ensure numeric)
        $grade = preg_replace('/\D/', '', (string)$grade);

        $fileName = "exam_tt_grade_{$grade}." . $ext;
        $fsPath = $this->uploadFsDir . $fileName; // full filesystem path
        $webPath = $this->uploadDir . $fileName;  // path to store in DB

        // Move uploaded file and check result
        if (!move_uploaded_file($file['tmp_name'], $fsPath)) {
            $_SESSION['exam_tt_msg'] = "Failed to move uploaded file.";
            header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
            exit;
        }

        $mime = $file['type'] ?? null;
        $size = $file['size'] ?? null;

        $existing = $this->model->getByGrade($grade);
        if ($existing) {
            $this->model->updateFile($grade, $webPath, $mime, $size);
            $_SESSION['exam_tt_msg'] = "Exam timetable updated for Grade {$grade}.";
        } else {
            $this->model->create($grade, $webPath, $mime, $size);
            $_SESSION['exam_tt_msg'] = "Exam timetable uploaded for Grade {$grade}.";
        }

        header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
        exit;
    }

    public function toggle()
    {
        session_start();
        $grade = $_POST['grade'] ?? null;
        $hidden = $_POST['hidden'] ?? null;
        if (!$grade) {
            $_SESSION['exam_tt_msg'] = "Grade required.";
            header("Location: /admin/dashboard?tab=Exam Time Table");
            exit;
        }
        $this->model->updateVisibility($grade, $hidden);
        $_SESSION['exam_tt_msg'] = $hidden ? "Timetable hidden." : "Timetable visible.";
        header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
        exit;
    }

    public function delete()
    {
        session_start();
        $grade = $_POST['grade'] ?? null;
        if (!$grade) {
            $_SESSION['exam_tt_msg'] = "Grade required.";
            header("Location: /admin/dashboard?tab=Exam Time Table");
            exit;
        }
        $rec = $this->model->getByGrade($grade);
        if ($rec && !empty($rec['file_path'])) {
            $file = $_SERVER['DOCUMENT_ROOT'] . $rec['file_path'];
            if (file_exists($file)) unlink($file);
            $this->model->delete($grade);
            $_SESSION['exam_tt_msg'] = "Timetable deleted.";
        } else {
            $_SESSION['exam_tt_msg'] = "No timetable to delete.";
        }
        header("Location: /admin/dashboard?tab=Exam Time Table&grade={$grade}");
        exit;
    }
}