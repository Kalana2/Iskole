<?php
require_once '../app/Core/Controller.php';
require_once '../app/Core/Session.php';
require_once '../app/Model/UserModel.php';

class AthuController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            // if ($user && password_verify($password, $user['password'])) {
            if ($user && $password == $user['password']) {
                $this->session->set('user', $user);
                header('Location: /dashboard');
            } else {
                $this->view('login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->view('login');
        }


    }

    public function logout()
    {
        Session::getInstance()->destroy();
        header('Location: /login');
    }

}