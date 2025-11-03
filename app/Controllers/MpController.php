<?php
require_once '../app/Core/UserController.php';

class Mpcontroller extends UserController
{

    public function index()
    {
        $this->view('mp/index');
    }

    public function createMp($data)
    {
        try {
            $this->userModel = $this->model('MpModel');
            $this->userModel->createMp($data);
            // $this->view('mp/success', ['message' => 'MP created successfully.']);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request to create MP: " . $e->getMessage());
        }
    }
}

