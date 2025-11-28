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
        $user = $userModel->getUserDetailsById((int) $userId);

        // Render the profile view and pass user data
        $this->view('templates/profile', ['user' => $user]);
    }

    public function edit()
    {
        $userId = $this->session->get('user_id') ?? $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->getUserDetailsById((int) $userId);
        $this->view('templates/profile_edit', ['user' => $user]);
    }

    public function photo()
    {
        $userId = $this->session->get('user_id') ?? $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->getUserDetailsById((int) $userId);
        $this->view('templates/profile_photo', ['user' => $user]);
    }
}
