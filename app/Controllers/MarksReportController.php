<?php
require_once __DIR__ . '/../Core/Controller.php';

class MarksReportController extends Controller
{
    public function index()
    {
        $this->view('marksReport/index');
    }

    public function getStudentMarks($studentId)
    {
        header("Content-Type: application/json");
        http_response_code(200);

        $model = $this->model("MarksReportModel");

        $subjects = $model->getSubjectsWithLatestTermMarks($studentId);
        $terms = $model->getTermWiseMarks($studentId);

        echo json_encode([
            "subjects" => $subjects ?: [],
            "terms" => $terms ?: []
        ]);
    }
}
