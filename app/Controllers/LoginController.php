<?php
require_once '../app/Core/Controller.php';
require_once '../app/Core/Session.php';
class LoginController extends Controller
{
    public function index()
    {
        $this->view('login/index');
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($email === '' || $password === '') {
            $this->view('login/index', [
                'error' => 'Please provide email and password.',
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Load user model
        $userModel = $this->model('UserModel');

        $user = $userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('login/index', [
                'error' => 'Invalid credentials.',
                'old' => ['email' => $email]
            ]);
            return;
        }

        $userId = isset($user['userID']) ? (int) $user['userID'] : (int) ($user['id'] ?? 0);
        $userRole = isset($user['role']) ? (int) $user['role'] : 0;
        $userEmail = $user['email'] ?? '';
        $userName = $user['fName'] ?? ($user['firstName'] ?? '');

        // Using Session helper
        $this->session->set('user_id', $userId);
        $this->session->set('userId', $userId);
        $this->session->set('user_email', $userEmail);
        $this->session->set('userEmail', $userEmail);
        $this->session->set('user_role', $userRole);
        $this->session->set('userRole', $userRole);
        $this->session->set('name', $userName);

        // Also ensure superglobal is set for any direct checks
        $_SESSION['user_id'] = $userId;
        $_SESSION['userId'] = $userId;
        $_SESSION['user_email'] = $userEmail;
        $_SESSION['userEmail'] = $userEmail;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['userRole'] = $userRole;


        $this->roleRedirect($user['role']);
        exit;
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit;
    }

    public function roleRedirect($role)
    {
        // ensure role is integer
        $role = (int) $role;
        switch ($role) {
            case 0:
                header('Location: /admin');
                break;
            case 1:
                header('Location: /mp');
                break;
            case 2:
                header('Location: /teacher');
                break;
            case 3:
                header('Location: /student');
                break;
            case 4:
                header('Location: /parent');
                break;
            default:
                header('Location: /notfound');
                break;
        }
        exit;
    }
}