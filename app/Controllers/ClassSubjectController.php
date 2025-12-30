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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->goBackToTab();

        $grade = (int)($_POST['grade'] ?? 0);
        $section = strtoupper(trim($_POST['section'] ?? ''));

        if ($grade < 1 || $grade > 13 || $section === '') {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => 'Grade/Section invalid.'];
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->classExists($grade, $section)) {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => "Class {$grade}{$section} already exists."];
            $this->goBackToTab();
        }

        if ($model->createClass($grade, $section)) {
            $_SESSION['cs_msg'] = ['type' => 'success', 'text' => "Class {$grade}{$section} created."];
        } else {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => "Failed to create class {$grade}{$section}."];
        }

        $this->goBackToTab();
    }

    public function deleteClass()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->goBackToTab();

        $classId = (int)($_POST['class_id'] ?? 0);
        if (!$classId) $this->goBackToTab();

        $model = $this->model('ClassSubjectModel');

        if ($model->deleteClass($classId)) {
            $_SESSION['cs_msg'] = ['type' => 'success', 'text' => 'Class deleted.'];
        } else {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => 'Failed to delete class. (Maybe used in other tables)'];
        }

        $this->goBackToTab();
    }

    public function createSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->goBackToTab();

        $grade = (int)($_POST['grade'] ?? 0);
        $name = trim($_POST['subjectName'] ?? '');

        if ($grade < 1 || $grade > 13 || $name === '') {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => 'Grade/Subject invalid.'];
            $this->goBackToTab();
        }

        $model = $this->model('ClassSubjectModel');

        if ($model->subjectExists($grade, $name)) {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => "Subject '{$name}' already exists for Grade {$grade}."];
            $this->goBackToTab();
        }

        if ($model->createSubject($grade, $name)) {
            $_SESSION['cs_msg'] = ['type' => 'success', 'text' => "Subject '{$name}' added to Grade {$grade}."];
        } else {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => "Failed to add subject '{$name}'."];
        }

        $this->goBackToTab();
    }

    public function deleteSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->goBackToTab();

        $subjectId = (int)($_POST['subject_id'] ?? 0);
        if (!$subjectId) $this->goBackToTab();

        $model = $this->model('ClassSubjectModel');

        if ($model->deleteSubject($subjectId)) {
            $_SESSION['cs_msg'] = ['type' => 'success', 'text' => 'Subject deleted.'];
        } else {
            $_SESSION['cs_msg'] = ['type' => 'error', 'text' => 'Failed to delete subject.'];
        }

        $this->goBackToTab();
    }
}
