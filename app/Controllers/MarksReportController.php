<?php

require_once __DIR__ . '/../Core/Controller.php';

class MarksReportController extends Controller
{
	public function index()
	{
		header('Location: /student?tab=My+Marks');
		exit;
	}

	public function myMarks()
	{
		header('Content-Type: application/json');

		$userId = $this->session->get('user_id');
		if (!$userId) {
			http_response_code(401);
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		// Get user role: 3 = student, 4 = parent
		$userRole = $this->session->get('userRole') ?? $this->session->get('user_role');

		try {
			$model = $this->model('MarksReportModel');

			$student = null;

			// If user is a parent (role 4), get their child's student record
			if ($userRole == 4) {
				$student = $model->getChildStudentByParentUserId($userId);
				if (!$student) {
					http_response_code(404);
					echo json_encode(['success' => false, 'message' => 'Child student record not found']);
					return;
				}
			} else {
				// User is a student (role 3) - get their own record
				$student = $model->getStudentByUserId($userId);
				if (!$student) {
					http_response_code(404);
					echo json_encode(['success' => false, 'message' => 'Student record not found']);
					return;
				}
			}

			$marks = $model->getMarksForStudent($student['studentID']);
			$subjects = $model->getAllSubjects();
			$ranks = $model->getClassRanksForStudent(
				intval($student['studentID']),
				intval($student['classID'])
			);

			echo json_encode([
				'success' => true,
				'student' => $student,
				'subjects' => $subjects,
				'marks' => $marks,
				'ranks' => $ranks,
				'isParentView' => ($userRole == 4)
			]);
		} catch (Exception $e) {
			error_log('MarksReport API error: ' . $e->getMessage());
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => 'Server error']);
		}
	}

	public function studentMarks()
{
    header('Content-Type: application/json');

    $userId = $this->session->get('user_id');
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        return;
    }

    $studentId = isset($_GET['studentID']) ? intval($_GET['studentID']) : 0;
    if ($studentId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
        return;
    }

    try {
        $model = $this->model('MarksReportModel');

        // searched student record
        $student = $model->getStudentByStudentId($studentId);
        if (!$student) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Student record not found']);
            return;
        }

        $marks = $model->getMarksForStudent($student['studentID']);
        $subjects = $model->getAllSubjects();
        $ranks = $model->getClassRanksForStudent(
            intval($student['studentID']),
            intval($student['classID'])
        );

        echo json_encode([
            'success' => true,
            'student' => $student,
            'subjects' => $subjects,
            'marks' => $marks,
            'ranks' => $ranks,
            'isParentView' => false
        ]);
    } catch (Exception $e) {
        error_log('MarksReport studentMarks API error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
}












}
