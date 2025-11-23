<?php

class AddNewUserController extends Controller
{
    protected $userRoleMap = ['admin' => 0, 'mp' => 1, 'teacher' => 2, 'student' => 3, 'parent' => 4];
    private function markLoaded($context = '')
    {
        $_SESSION['addNewUser_loaded'] = 'AddNewUserController loaded' . ($context ? ' (' . $context . ')' : '') . ' @ ' . date('H:i:s');
    }

    public function index()
    {
        $this->markLoaded('index');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin?tab=Management'));
        exit;
    }

    public function submit()
    {
        $this->markLoaded('submit');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mgmt_msg'] = 'AddNewUserController reached (non-POST).';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin?tab=Management'));
            exit;
        }

        // Required minimal fields
        $role = $_POST['role'] ?? null; // matches select name="role" in the form
        $email = trim($_POST['email'] ?? '');
        $fName = trim($_POST['fName'] ?? '');
        $lName = trim($_POST['lName'] ?? '');

        if (!$role || !$email || !$fName || !$lName) {
            $_SESSION['mgmt_msg'] = 'AddNewUserController: Missing required fields.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin?tab=Management'));
            exit;
        }

        $now = date('Y-m-d H:i:s');

        // Map posted fields to UserModel::createUser expected keys
        $base = [
            'gender' => $_POST['gender'] ?? null,
            'email' => $email,
            'phone' => trim($_POST['phone'] ?? ''),
            'createDate' => $now,
            'role' => $role,
            'active' => 1,
            'dateOfBirth' => $_POST['dateOfBirth'] ?? null,
            'password' => password_hash($fName, PASSWORD_BCRYPT), // initial password; update later
            'pwdChanged' => 0,
            'address_line1' => $_POST['addressLine1'] ?? '',
            'address_line2' => $_POST['addressLine2'] ?? '',
            'address_line3' => $_POST['addressLine3'] ?? '',
            'fName' => $fName,
            'lName' => $lName,
        ];

        try {
            switch ($role) {
                case 'mp': {
                    $model = $this->model('MpModel');
                    // $data['role'] = $this->userRoleMap['mp'];
                    $data['role'] = 1;
                    $data = $base + [
                        'nic' => $_POST['nic'] ?? null,
                    ];
                    $model->createMp($data);
                    break;
                }
                case 'teacher': {
                    $model = $this->model('TeacherModel');
                    // $data['role'] = $this->userRoleMap['teacher'];
                    $data['role'] = 2;
                    $data = $base + [
                        'nic' => $_POST['nic'] ?? null,
                        'grade' => $_POST['grade'] ?? null,
                        'classId' => $_POST['class'] ?? null, // map select "class" to classId
                        'subject' => $_POST['subject'] ?? null,
                    ];
                    $model->createTeacher($data);
                    break;
                }
                case 'student': {
                    $model = $this->model('StudentModel');
                    // $data['role'] = $this->userRoleMap['student'];
                    $data['role'] = 3;
                    $data = $base + [
                        'grade' => $_POST['grade'] ?? null,
                        'classId' => $_POST['class'] ?? null, // optional/unknown in form
                    ];
                    $model->createStudent($data);
                    break;
                }
                case 'parent': {
                    $model = $this->model('ParentModel');
                    $data['role'] = $this->userRoleMap['parent'];
                    // $data['role'] = 4;
                    $data = $base + [
                        'relationshipType' => $_POST['relationship'] ?? null,
                        'studentId' => $_POST['studentIndex'] ?? null, // assuming index is id or will be resolved in model
                        'nic' => $_POST['nic'] ?? null,
                    ];
                    $model->createParent($data);
                    break;
                }
                default:
                    throw new Exception('Invalid role submitted.');
            }

            $_SESSION['mgmt_msg'] = 'AddNewUserController: User added successfully.';
        } catch (Exception $e) {
            $_SESSION['mgmt_msg'] = 'AddNewUserController Error: ' . $e->getMessage();
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin?tab=Management'));
        exit;
    }
}

