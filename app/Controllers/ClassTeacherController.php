<?php
require_once __DIR__ . '/../Core/Controller.php';

class ClassTeacherController extends Controller
{
    private function goBackToTab()
    {
        header('Location: /index.php?url=admin&tab=Assign%20Class%20Teacher');
        exit;
    }

    public function index()
    {
        $userId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $model = $this->model('ClassTeacherModel');

        $classesWithTeachers = $model->getAllClassesWithTeachers();
        $teachers = $model->getAllTeachers();

        $flash = $_SESSION['ct_msg'] ?? null;
        unset($_SESSION['ct_msg']);

        $this->view('admin/index', [
            'tab' => 'Assign Class Teacher',
            'classesWithTeachers' => $classesWithTeachers,
            'teachers' => $teachers,
            'flash' => $flash,
        ]);
    }

    public function assignTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $classId = (int)($_POST['class_id'] ?? 0);
        $teacherId = (int)($_POST['teacher_id'] ?? 0);

        if ($classId <= 0 || $teacherId <= 0) {
            $_SESSION['ct_msg'] = ['type' => 'error', 'text' => 'Invalid class or teacher selection.'];
            $this->goBackToTab();
        }

        $model = $this->model('ClassTeacherModel');

        if ($model->assignClassTeacher($classId, $teacherId)) {
            $_SESSION['ct_msg'] = ['type' => 'success', 'text' => 'Class teacher assigned successfully!'];
        } else {
            $_SESSION['ct_msg'] = ['type' => 'error', 'text' => 'Failed to assign class teacher.'];
        }

        $this->goBackToTab();
    }

    public function removeTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $classId = (int)($_POST['class_id'] ?? 0);

        if ($classId <= 0) {
            $_SESSION['ct_msg'] = ['type' => 'error', 'text' => 'Invalid class selection.'];
            $this->goBackToTab();
        }

        $model = $this->model('ClassTeacherModel');

        if ($model->removeClassTeacher($classId)) {
            $_SESSION['ct_msg'] = ['type' => 'success', 'text' => 'Class teacher removed successfully!'];
        } else {
            $_SESSION['ct_msg'] = ['type' => 'error', 'text' => 'Failed to remove class teacher.'];
        }

        $this->goBackToTab();
    }
}
