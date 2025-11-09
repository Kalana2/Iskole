<?php
class AdminController extends Controller
{
    public function index()
    {
        $this->view('admin/index');
    }

    // timetable management page
    public function timeTable()
    {
        $this->view('admin/timeTable');
    }
}
