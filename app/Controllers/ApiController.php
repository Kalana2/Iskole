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

        $action = $_GET['action'] ?? $_POST['action'] ?? '';

        if ($action === 'get' && isset($_GET['id'])) {
            $this->getUserById($_GET['id']);
        } elseif ($action === 'search' && isset($_GET['q'])) {
            $this->searchUsers($_GET['q']);
        } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateUser();
        } elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->deleteUser();
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

    private function updateUser()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

            // Get JSON data from request body
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (!$data || !isset($data['userID'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
                return;
            }

            $userId = $data['userID'];

            // Validate required fields
            if (empty($data['firstName']) || empty($data['lastName']) || empty($data['email'])) {
                echo json_encode(['success' => false, 'message' => 'First name, last name, and email are required']);
                return;
            }

            $userDirectory = new UserDirectoryController();
            $result = $userDirectory->updateUser($userId, $data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update user']);
            }
        } catch (Exception $e) {
            error_log('API Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function deleteUser()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

            // Get JSON data from request body
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            // Log the received data for debugging
            error_log('Delete request data: ' . print_r($data, true));

            if (!$data || !isset($data['userID'])) {
                error_log('Delete request failed: userID not found in data');
                echo json_encode(['success' => false, 'message' => 'User ID is required', 'received' => $data]);
                return;
            }

            $userId = (int) $data['userID'];

            $userDirectory = new UserDirectoryController();
            $result = $userDirectory->deleteUser($userId);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete user or user not found']);
            }
        } catch (Exception $e) {
            error_log('API Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
