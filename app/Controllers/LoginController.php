<?php
require_once '../app/Core/Controller.php';
require_once '../app/Core/Session.php';
require_once '../app/Core/EmailService.php';

class LoginController extends Controller
{
    private $emailService;

    public function __construct()
    {
        parent::__construct();
        $this->emailService = new EmailService();
    }

    public function index()
    {
        $this->view('login/index');
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($email === '' || $password === '') {
            $this->view('login/index', [
                'error' => 'Please provide email and password.',
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Load user model
        $userModel = $this->model('UserModel');

        $user = $userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('login/index', [
                'error' => 'Invalid credentials.',
                'old' => ['email' => $email]
            ]);
            return;
        }

        $userId = isset($user['userID']) ? (int) $user['userID'] : (int) ($user['id'] ?? 0);
        $userRole = isset($user['role']) ? (int) $user['role'] : 0;
        $userEmail = $user['email'] ?? '';
        $userfirstName = $user['firstName'] ?? '';
        $userlastName = $user['lastName'] ?? '';
        $userName = $userfirstName . ' ' . $userlastName;

        // Using Session helper
        $this->session->set('user_id', $userId);
        $this->session->set('userId', $userId);
        $this->session->set('user_email', $userEmail);
        $this->session->set('userEmail', $userEmail);
        $this->session->set('user_role', $userRole);
        $this->session->set('userRole', $userRole);
        $this->session->set('name', $userName);

        // Also ensure superglobal is set for any direct checks
        $_SESSION['user_id'] = $userId;
        $_SESSION['userId'] = $userId;
        $_SESSION['user_email'] = $userEmail;
        $_SESSION['userEmail'] = $userEmail;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['userRole'] = $userRole;


        $this->roleRedirect($user['role']);
        exit;
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit;
    }

    public function resetPassword()
    {
        // If GET request, show the reset password form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('login/resetPasswordIndex');
            return;
        }

        // Handle POST request for password reset
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login/resetPassword');
            exit;
        }

        $action = isset($_POST['action']) ? $_POST['action'] : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        // Handle resend OTP via AJAX
        if ($action === 'resend_otp') {
            header('Content-Type: application/json');

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email']);
                exit;
            }

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);

            if ($user) {
                $otp = $this->generateOTP();
                $this->session->set('reset_otp', $otp);
                $this->session->set('reset_email', $email);
                $this->session->set('otp_expiry', time() + 600); // 10 minutes

                // Send OTP via email
                $emailSent = $this->emailService->sendOTP($email, $otp, 10);

                if (!$emailSent) {
                    error_log("⚠️ Warning: Failed to send OTP email to $email");
                }
            }

            echo json_encode(['success' => true, 'message' => 'OTP resent successfully']);
            exit;
        }

        // Step 1: Send OTP
        if ($action === 'send_otp') {
            if ($email === '') {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'Please provide your email address.',
                    'old' => ['email' => $email]
                ]);
                return;
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'Please provide a valid email address.',
                    'old' => ['email' => $email]
                ]);
                return;
            }

            // Load user model
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);

            if (!$user) {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'No account found with this email address.',
                    'old' => ['email' => $email]
                ]);
                return;
            }

            // Generate OTP
            $otp = $this->generateOTP();

            // Store OTP in session
            $this->session->set('reset_otp', $otp);
            $this->session->set('reset_email', $email);
            $this->session->set('otp_expiry', time() + 600); // 10 minutes expiry

            // Send OTP via email
            $emailSent = $this->emailService->sendOTP($email, $otp, 10);

            if (!$emailSent) {
                error_log("⚠️ Warning: Failed to send OTP email to $email, but OTP was generated");
            }

            $this->view('login/resetPasswordIndex', [
                'success' => 'OTP has been sent to your email address.',
                'otpSent' => true,
                'email' => $email
            ]);
            return;
        }

        // Step 2: Verify OTP
        if ($action === 'verify_otp') {
            $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';

            if ($email === '' || $otp === '') {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'Please provide both email and OTP.',
                    'otpSent' => true,
                    'email' => $email
                ]);
                return;
            }

            // Verify OTP
            $storedOTP = $this->session->get('reset_otp');
            $storedEmail = $this->session->get('reset_email');
            $otpExpiry = $this->session->get('otp_expiry');

            if (!$storedOTP || !$storedEmail || !$otpExpiry) {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'OTP session expired. Please request a new OTP.',
                ]);
                return;
            }

            if (time() > $otpExpiry) {
                $this->session->delete('reset_otp');
                $this->session->delete('reset_email');
                $this->session->delete('otp_expiry');

                $this->view('login/resetPasswordIndex', [
                    'error' => 'OTP has expired. Please request a new OTP.',
                ]);
                return;
            }

            if ($storedEmail !== $email) {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'Invalid email address.',
                    'otpSent' => true,
                    'email' => $email
                ]);
                return;
            }

            if ($storedOTP !== $otp) {
                $this->view('login/resetPasswordIndex', [
                    'error' => 'Invalid OTP. Please try again.',
                    'otpSent' => true,
                    'email' => $email
                ]);
                return;
            }

            // OTP verified successfully
            // Generate reset token and redirect to set new password page
            $resetToken = bin2hex(random_bytes(32));
            $this->session->set('reset_token', $resetToken);
            $this->session->set('reset_token_email', $email);
            $this->session->set('reset_token_expiry', time() + 1800); // 30 minutes

            // Clear OTP session
            $this->session->delete('reset_otp');
            $this->session->delete('otp_expiry');

            // Redirect to set new password page
            header('Location: /login/setNewPassword?token=' . $resetToken);
            exit;
        }

        // Invalid action
        header('Location: /login/resetPassword');
        exit;
    }

    private function generateOTP()
    {
        return sprintf("%06d", mt_rand(100000, 999999));
    }

    public function setNewPassword()
    {
        // If GET request with token, show the set new password form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = isset($_GET['token']) ? $_GET['token'] : '';

            if (empty($token)) {
                $this->view('login/setNewPasswordIndex', [
                    'error' => 'Invalid or missing reset token.',
                    'token' => ''
                ]);
                return;
            }

            // Verify token from session
            $storedToken = $this->session->get('reset_token');
            $tokenExpiry = $this->session->get('reset_token_expiry');

            if (!$storedToken || $storedToken !== $token) {
                $this->view('login/setNewPasswordIndex', [
                    'error' => 'Invalid reset token.',
                    'token' => ''
                ]);
                return;
            }

            if (time() > $tokenExpiry) {
                $this->session->delete('reset_token');
                $this->session->delete('reset_token_email');
                $this->session->delete('reset_token_expiry');

                $this->view('login/setNewPasswordIndex', [
                    'error' => 'Reset link has expired. Please request a new password reset.',
                    'token' => ''
                ]);
                return;
            }

            $this->view('login/setNewPasswordIndex', [
                'token' => $token
            ]);
            return;
        }

        // Handle POST request for setting new password
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $token = isset($_POST['token']) ? trim($_POST['token']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Validation
        if (empty($token)) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Invalid reset token.',
                'token' => $token
            ]);
            return;
        }

        // Verify token from session
        $storedToken = $this->session->get('reset_token');
        $tokenExpiry = $this->session->get('reset_token_expiry');
        $email = $this->session->get('reset_token_email');

        if (!$storedToken || $storedToken !== $token) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Invalid reset token.',
                'token' => ''
            ]);
            return;
        }

        if (time() > $tokenExpiry) {
            $this->session->delete('reset_token');
            $this->session->delete('reset_token_email');
            $this->session->delete('reset_token_expiry');

            $this->view('login/setNewPasswordIndex', [
                'error' => 'Reset link has expired. Please request a new password reset.',
                'token' => ''
            ]);
            return;
        }

        if (empty($password) || empty($confirmPassword)) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Please fill in all fields.',
                'token' => $token
            ]);
            return;
        }

        if (strlen($password) < 8) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Password must be at least 8 characters long.',
                'token' => $token
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Passwords do not match.',
                'token' => $token
            ]);
            return;
        }

        // Update password in database
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'User not found.',
                'token' => ''
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = isset($user['userID']) ? $user['userID'] : $user['id'];

        // Update password
        $result = $userModel->updatePassword($userId, $hashedPassword);

        if (!$result) {
            $this->view('login/setNewPasswordIndex', [
                'error' => 'Failed to update password. Please try again.',
                'token' => $token
            ]);
            return;
        }

        // Clear session data
        $this->session->delete('reset_token');
        $this->session->delete('reset_token_email');
        $this->session->delete('reset_token_expiry');
        $this->session->delete('reset_email');

        // Redirect to login with success message
        $this->session->set('password_reset_success', true);
        header('Location: /login');
        exit;
    }

    public function roleRedirect($role)
    {
        // ensure role is integer
        $role = (int) $role;
        switch ($role) {
            case 0:
                header('Location: /admin');
                break;
            case 1:
                header('Location: /mp');
                break;
            case 2:
                header('Location: /teacher');
                break;
            case 3:
                header('Location: /student');
                break;
            case 4:
                header('Location: /parent');
                break;
            default:
                header('Location: /notfound');
                break;
        }
        exit;
    }
}