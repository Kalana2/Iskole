<?php
require_once __DIR__ . '/../Core/Controller.php';

class ClassSubjectController extends Controller
{
    private function goBackToTab()
    {
        header('Location: /index.php?url=admin&tab=Class%20%26%20Subjects');
        exit;
    }

    public function index()
    {
        $userId = $_SESSION['userId'] ?? ($_SESSION['user_id'] ?? 0);
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $model = $this->model('ClassSubjectModel');

        $classes  = $model->getAllClasses();
        $subjects = $model->getAllSubjects();

        $flash = $_SESSION['cs_msg'] ?? null;
        unset($_SESSION['cs_msg']);

        $this->view('admin/index', [
            'tab'      => 'Class & Subjects',
            'classes'  => $classes,
            'subjects' => $subjects,
            'flash'    => $flash,
        ]);
    }

    public function createClass()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $grade = (int)($_POST['grade'] ?? 0);
        $section = strtoupper(trim($_POST['section'] ?? ''));

        if ($grade < 1 || $grade > 13) {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Grade must be between 1 and 13.'
            ];
            $this->goBackToTab();
        }

        // Only one capital letter A-Z allowed
        if (!preg_match('/^[A-Za-z]$/', $section)) {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Section must be a single English letter only.'
            ];
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->classExists($grade, $section)) {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => "Class {$grade}{$section} already exists."
            ];
            $this->goBackToTab();
        }

        if ($model->createClass($grade, $section)) {
            $_SESSION['cs_msg'] = [
                'type' => 'success',
                'text' => 'Class successfully added.'
            ];
        } else {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Failed to add class.'
            ];
        }

        $this->goBackToTab();
    }

    public function deleteClass()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $classId = (int)($_POST['class_id'] ?? 0);
        if (!$classId) {
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->deleteClass($classId)) {
            $_SESSION['cs_msg'] = [
                'type' => 'success',
                'text' => 'Class removed successfully.'
            ];
        } else {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Failed to remove class. It may be used in other tables.'
            ];
        }

        $this->goBackToTab();
    }

    public function createSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $name = trim($_POST['subjectName'] ?? '');

        if ($name === '') {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Subject name is required.'
            ];
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->subjectExists($name)) {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => "Subject '{$name}' already exists."
            ];
            $this->goBackToTab();
        }

        if ($model->createSubject($name)) {
            $_SESSION['cs_msg'] = [
                'type' => 'success',
                'text' => 'Subject successfully added.'
            ];
        } else {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Failed to add subject.'
            ];
        }

        $this->goBackToTab();
    }

    public function deleteSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->goBackToTab();
        }

        $subjectId = (int)($_POST['subject_id'] ?? 0);
        if (!$subjectId) {
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->deleteSubject($subjectId)) {
            $_SESSION['cs_msg'] = [
                'type' => 'success',
                'text' => 'Subject removed successfully.'
            ];
        } else {
            $_SESSION['cs_msg'] = [
                'type' => 'error',
                'text' => 'Failed to remove subject.'
            ];
        }

        $this->goBackToTab();
    }
}
