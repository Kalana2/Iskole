<?php
require_once __DIR__ . '/../Core/Controller.php';

class ParentController extends Controller
{
    public function index()
    {
        $this->view('parent/index');
    }
}