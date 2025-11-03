<?php
class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }

    public function CreateUser($data)
    {
        try {
            $this->userModel->createUser($data);
            // $this->view('user/success', ['message' => 'User created successfully.']);
        } catch (Exception $e) {
            // $this->view('user/error', ['error' => $e->getMessage()]);
            throw new Exception("Error Processing Request to create User: " . $e->getMessage());
        }
    }


}