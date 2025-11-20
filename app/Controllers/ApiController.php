<?php
require_once __DIR__ . '/userDirectoryController.php';

class ApiController extends Controller
{
    protected $userRoleMap = ['Admin' => 0, 'Manager' => 1, 'Teacher' => 2, 'Student' => 3, 'Parent' => 4];

    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'API endpoint requires an action']);
    }

    public function users()
    {
        header('Content-Type: application/json');

        $action = $_GET['action'] ?? '';

        if ($action === 'get' && isset($_GET['id'])) {
            $this->getUserById($_GET['id']);
        } elseif ($action === 'search' && isset($_GET['q'])) {
            $this->searchUsers($_GET['q']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
    }

    private function searchUsers($query)
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

            $userDirectory = new UserDirectoryController();
            $users = $userDirectory->searchUsers($query);

            echo json_encode([
                'success' => true,
                'users' => $users
            ]);
        } catch (Exception $e) {
            error_log('API Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getUserById($userId)
    {
        try {
            $userDirectory = new UserDirectoryController();
            $user = $userDirectory->getUserById($userId);

            if ($user) {
                echo json_encode([
                    'success' => true,
                    'userID' => $user['userID'],
                    'firstName' => $user['firstName'] ?? '',
                    'lastName' => $user['lastName'] ?? '',
                    'email' => $user['email'] ?? '',
                    'phone' => $user['phone'] ?? '',
                    'gender' => $user['gender'] ?? '',
                    'dateOfBirth' => $user['dateOfBirth'] ?? '',
                    'studentID' => $user['studentID'] ?? '',
                    'address_line1' => $user['address_line1'] ?? '',
                    'address_line2' => $user['address_line2'] ?? '',
                    'address_line3' => $user['address_line3'] ?? '',
                    'role' => $user['role'] ?? ''
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
