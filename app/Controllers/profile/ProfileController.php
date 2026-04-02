<?php
require_once '../app/Core/Controller.php';
require_once '../app/Core/Session.php';
class ProfileController extends Controller
{
    public function index()
    {
        // Ensure user is logged in; App constructor normally enforces this but double-check here
        $userId = $this->session->get('user_id') ?? $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $userModel = $this->model('UserModel');
        $teacherModel = $this->model('TeacherModel');
        $subjectModel = $this->model('SubjectModel');
        $studentModel = $this->model('StudentModel');
        $parentModel = $this->model('ParentModel');
        $classModel = $this->model('ClassModel');

        $user = $userModel->getUserDetailsById((int) $userId);

        $managerModel = $this->model('MpModel');
        // $manager = $managerModel->getManagerByUserId((int) $userId);
        // $user['manager'] = $manager;




        $teacher = $teacherModel->getTeacherByUserId((int) $userId);
        $user['teacher'] = $teacher;

        $student = $studentModel->getStudentByUserId((int) $userId);
        $user['student'] = $student;

        if ($user['role'] === 2 /* teacher */) {

            $subject = $subjectModel->getSubjectById((int) ($teacher['subjectID'] ?? 0));
        } elseif ($user['role'] === 3 /* student */) {
            $subject = $subjectModel->getSubjectById((int) ($student['subjectID'] ?? 0));
        } else {
            $subject = null;
        }
        $user['subject'] = $subject['subjectName'] ?? '';

        if ($user['role'] === 2 /* teacher */) {
            $class = $classModel->getClassById((int) ($teacher['classID'] ?? 0));
        } elseif ($user['role'] === 3 /* student */) {
            $class = $classModel->getClassById((int) ($student['classID'] ?? 0));

        }
        $user['class'] = $class['class'] ?? '';
        $user['grade'] = $class['grade'] ?? '';

        $parent = $parentModel->getParentByUserId((int) $userId);
        $student = $studentModel->getStudentById((int) ($parent['studentID'] ?? 0));
        $user['parent'] = $parent;
        $user['student'] = $student;

        // Render the profile view and pass user data
        $this->view('profile/profile', ['user' => $user]);
    }


}
