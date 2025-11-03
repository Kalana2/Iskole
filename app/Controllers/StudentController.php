<?php

require_once __DIR__ . '/../Core/Controller.php';

class StudentController extends Controller
{
    public function index()
    {
        $this->view('student/index');
    }
}