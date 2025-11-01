<?php
class Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            Session::getInstance();
        }
    }

    protected function view($view, $data = [])
    {
        extract($data);
        require_once "../app/Views/$view.php";
    }

    protected function model($model)
    {
        // Load model from the singular 'Model' directory used by the project
        require_once "../app/Model/$model.php";
        return new $model();
    }
}