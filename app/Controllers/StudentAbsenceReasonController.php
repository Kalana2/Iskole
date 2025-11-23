<?php

class StudentAbsenceReasonController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('StudentAbsenceReasonModel');
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mgmt_msg'] = 'Invalid request method.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $userId = $this->session ? $this->session->get('user_id') : ($_SESSION['user_id'] ?? null);
        if (!$userId) {
            $_SESSION['mgmt_msg'] = 'User not authenticated.';
            header('Location: /login');
            exit;
        }

        $parentModel = $this->model('ParentModel');
        $parent = $parentModel->getParentByUserId($userId);
        if (!$parent) {
            $_SESSION['mgmt_msg'] = 'Parent record not found.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $studentModel = $this->model('StudentModel');
        $student = $studentModel->getStudentById($parent['studentID']);
        if (!$student) {
            $_SESSION['mgmt_msg'] = 'Student record not found.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $teacherModel = $this->model('TeacherModel');
        $teacher = $teacherModel->getTeacherByClass($parent['grade'], $student['classID'] ?? 0);
        var_dump($teacher);

        $required = ['reason', 'fromDate', 'toDate'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mgmt_msg'] = 'Missing required field: ' . $field;
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }
        }

        $data = [
            'parentId' => $parent['id'] ?? null,
            'studentId' => $parent['studentID'],
            'teacherId' => $teacherClass,
            'reason' => trim($_POST['reason']),
            'fromDate' => $_POST['fromDate'],
            'toDate' => $_POST['toDate'],
        ];

        if ($this->model->submitAbsenceReason($data)) {
            $_SESSION['mgmt_msg'] = 'Absence reason submitted successfully.';
        } else {
            $_SESSION['mgmt_msg'] = 'Failed to submit absence reason.';
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }
}