<?php
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

        $user = $userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->view('login/index', [
                'error' => 'Invalid credentials.',
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Save session and redirect
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_email'] = $user['email'];

        header('Location: /home');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}