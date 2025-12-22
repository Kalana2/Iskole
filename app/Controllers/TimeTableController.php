<?php
require_once __DIR__ . '/../Core/Controller.php';

class TimeTableController extends Controller
{
    private function baseData($model, $selectedGrade = '')
    {
        return [
            'grades' => $model->getGrades(),
            'classes' => $selectedGrade
                ? $model->getClassesByGrade($selectedGrade)
                : [],
            'subjects' => $model->getSubjects(),
            'teachersMapping' => $model->getTeachersMapping(),
            'days' => $model->getDays(),       // keep dayID + day
            'periods' => $model->getPeriods(),
        ];
    }

    // Initial page load
    public function index()
    {
        $model = $this->model('TimeTableModel');

        $selectedGrade = $_GET['grade'] ?? '';
        $selectedClass = $_GET['class'] ?? '';

        $data = $this->baseData($model, $selectedGrade);

        $data['selectedGrade'] = $selectedGrade;
        $data['selectedClass'] = $selectedClass;
        $data['timetable'] = $selectedClass
            ? $model->getTimetableByClass($selectedClass)
            : [];

        $this->view('admin/timeTable', $data);
    }

    // Alias (optional route compatibility)
    public function getTimetable($classId = null)
    {
        $_GET['class'] = $classId;
        $this->index();
    }

    // Save or update a timetable slot
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $required = ['teacherId', 'dayId', 'periodId', 'classId', 'subjectId'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing {$field}"]);
                return;
            }
        }

        $model = $this->model('TimeTableModel');

        $result = $model->insertOrUpdateSlot(
            $_POST['teacherId'],
            $_POST['dayId'],
            $_POST['periodId'],
            $_POST['classId'],
            $_POST['subjectId']
        );

        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Slot saved successfully' : 'Failed to save slot'
        ]);
    }

    // Update slot explicitly
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        if (empty($_POST['id']) || empty($_POST['subjectId']) || empty($_POST['teacherId'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $model = $this->model('TimeTableModel');
        $result = $model->updateSlot(
            $_POST['id'],
            $_POST['subjectId'],
            $_POST['teacherId']
        );

        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Slot updated successfully' : 'Failed to update slot'
        ]);
    }

    // Delete slot
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        if (empty($_POST['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing slot ID']);
            return;
        }

        $model = $this->model('TimeTableModel');
        $result = $model->deleteSlot($_POST['id']);

        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Slot deleted successfully' : 'Failed to delete slot'
        ]);
    }

    // API: get teachers by subject
    public function getTeachersBySubject()
    {
        header('Content-Type: application/json');

        if (empty($_GET['subjectId'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing subjectId']);
            return;
        }

        $model = $this->model('TimeTableModel');
        echo json_encode([
            'teachers' => $model->getTeachersBySubject($_GET['subjectId'])
        ]);
    }
}
