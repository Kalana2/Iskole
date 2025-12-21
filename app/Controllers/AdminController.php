<?php
class AdminController extends Controller
{
    public function index()
    {
        // Handle announcement actions
        if (isset($_GET['action']) && in_array($_GET['action'], ['delete', 'update'])) {
            $this->handleAnnouncementAction($_GET['action']);
            return;
        }
        
        // Handle timetable actions
        if (isset($_GET['timetable_action']) && in_array($_GET['timetable_action'], ['create', 'read', 'update', 'delete'])) {
            $this->handleTimetableAction($_GET['timetable_action']);
            return;
        }

        $this->view('admin/index');
    }

    private function handleAnnouncementAction($action)
    {
        switch ($action) {
            case 'delete':
                include_once __DIR__ . '/announcement/deleteAnnouncementController.php';
                break;
            case 'update':
                include_once __DIR__ . '/announcement/updateAnnouncementController.php';
                break;
        }
    }

    private function handleTimetableAction($action)
    {
        switch ($action) {
            case 'create':
                include_once __DIR__ . '/timeTable/createTimetableController.php';
                break;
            case 'read':
                include_once __DIR__ . '/timeTable/readTimetableController.php';
                break;
            case 'update':
                include_once __DIR__ . '/timeTable/updateTimetableController.php';
                break;
            case 'delete':
                include_once __DIR__ . '/timeTable/deleteTimetableController.php';
                break;
        }
    }

    // timetable management page
    public function timeTable()
    {
        $this->view('admin/timeTable');
    }

    // Upload exam timetable image
    public function uploadExamTimeTable()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['exam_image'])) {
            $grade = isset($_POST['grade']) ? preg_replace('/[^0-9A-Za-z_-]/', '', $_POST['grade']) : 'default';
            $file = $_FILES['exam_image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                if (!isset($allowed[$mime])) {
                    $_SESSION['exam_tt_msg'] = 'Invalid image type.';
                    header('Location: /admin?tab=Exam%20Time%20Table');
                    exit;
                }
                $ext = $allowed[$mime];
                $dir = __DIR__ . '/../../public/assets/exam_timetable_images';
                if (!is_dir($dir)) {
                    @mkdir($dir, 0777, true);
                }
                $filename = 'exam_tt_' . $grade . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $targetPath = $dir . '/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $publicPath = '/assets/exam_timetable_images/' . $filename;
                    $metaFile = __DIR__ . '/../../public/assets/exam_timetable.json';
                    $meta = [];
                    if (file_exists($metaFile)) {
                        $meta = json_decode(file_get_contents($metaFile), true) ?: [];
                    }
                    $meta[$grade] = [
                        'file' => $publicPath,
                        'hidden' => false,
                        'uploaded_at' => date('c')
                    ];
                    file_put_contents($metaFile, json_encode($meta, JSON_PRETTY_PRINT));
                    $_SESSION['exam_tt_msg'] = 'Exam timetable uploaded successfully for Grade ' . htmlspecialchars($grade);
                } else {
                    $_SESSION['exam_tt_msg'] = 'Failed to move uploaded file.';
                }
            } else {
                $_SESSION['exam_tt_msg'] = 'Upload error: ' . $file['error'];
            }
        }
        header('Location: /admin?tab=Exam%20Time%20Table');
        exit;
    }

    // Toggle hide/show exam timetable
    public function toggleExamTimeTable()
    {
        $metaFile = __DIR__ . '/../../public/assets/exam_timetable.json';
        $meta = [];
        if (file_exists($metaFile)) {
            $json = file_get_contents($metaFile);
            $meta = json_decode($json, true) ?: [];
        }
        $grade = isset($_POST['grade']) ? preg_replace('/[^0-9A-Za-z_-]/', '', $_POST['grade']) : 'default';
        $hidden = isset($_POST['hidden']) ? (bool) $_POST['hidden'] : false;
        if (!isset($meta[$grade])) {
            $meta[$grade] = [];
        }
        $meta[$grade]['hidden'] = $hidden;
        file_put_contents($metaFile, json_encode($meta, JSON_PRETTY_PRINT));
        $_SESSION['exam_tt_msg'] = ($hidden ? 'Hidden' : 'Visible') . ' for Grade ' . htmlspecialchars($grade);
        header('Location: /admin?tab=Exam%20Time%20Table');
        exit;
    }
}