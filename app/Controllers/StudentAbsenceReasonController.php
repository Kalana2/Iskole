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

        // Get parent information
        $parentModel = $this->model('ParentModel');
        $parent = $parentModel->getParentByUserId($userId);
        if (!$parent) {
            $_SESSION['mgmt_msg'] = 'Parent record not found.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $required = ['reason', 'fromDate', 'toDate'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mgmt_msg'] = 'Missing required field: ' . $field;
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }
        }

        $data = [
            'parentId' => $parent['parentID'] ?? null,
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

    public function edit()
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

        $required = ['reasonId', 'reason', 'fromDate', 'toDate'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mgmt_msg'] = 'Missing required field: ' . $field;
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }
        }

        $data = [
            'reasonId' => $_POST['reasonId'],
            'reason' => trim($_POST['reason']),
            'fromDate' => $_POST['fromDate'],
            'toDate' => $_POST['toDate'],
        ];

        if ($this->model->updateAbsenceReason($data)) {
            $_SESSION['mgmt_msg'] = 'Absence reason updated successfully.';
        } else {
            $_SESSION['mgmt_msg'] = 'Failed to update absence reason.';
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    public function delete()
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

        if (empty($_POST['reasonId'])) {
            $_SESSION['mgmt_msg'] = 'Missing reason ID.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        if ($this->model->deleteAbsenceReason($_POST['reasonId'])) {
            $_SESSION['mgmt_msg'] = 'Absence reason deleted successfully.';
        } else {
            $_SESSION['mgmt_msg'] = 'Failed to delete absence reason.';
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    public function viewAllAbsences()
    {
        $absences = $this->model->getAllAbsenceReasons();
        return $absences;
    }
    public function viewAbsenceByClass($classId, $grade)
    {
        $absences = $this->model->getAbsenceReasonsByClass($classId, $grade);

        return $absences;
    }

    public function viewAbsencesByUserId($userId)
    {
        $parentModel = $this->model('ParentModel');
        $parent = $parentModel->getParentByUserId($userId);
        var_dump("inside controller");
        var_dump($parent);

        $absences = $this->model->getAbsenceReasonsByParentId($parent['parentID']);
        var_dump("inside controller");
        var_dump($absences);
        return $absences;
    }
}