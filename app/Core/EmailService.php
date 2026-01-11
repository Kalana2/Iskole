<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailService
{
    private $mailer;
    private $config;
    private $developmentMode;
    private $logEmails;

    public function __construct()
    {
        $this->config = $this->loadConfig();
        $this->developmentMode = $this->config['development_mode'] ?? true;
        $this->logEmails = $this->config['log_emails'] ?? true;

        $this->mailer = new PHPMailer(true);
        $this->configureSMTP();
    }

    private function configureSMTP()
    {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = $this->config['smtp_auth'];
            $this->mailer->Username = $this->config['smtp_username'];
            $this->mailer->Password = $this->config['smtp_password'];
            $this->mailer->SMTPSecure = $this->config['smtp_secure'];
            $this->mailer->Port = $this->config['smtp_port'];
            $this->mailer->CharSet = $this->config['charset'];

            // Enable debug output in development
            if ($this->developmentMode && $this->config['debug'] > 0) {
                $this->mailer->SMTPDebug = $this->config['debug'];
                $this->mailer->Debugoutput = function ($str, $level) {
                    error_log("PHPMailer Debug [$level]: $str");
                };
            } else {
                $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
            }

            // From address
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);

        } catch (Exception $e) {
            error_log("EmailService Configuration Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send OTP email for password reset
     * @param string $email Recipient email address
     * @param string $otp The OTP code to send
     * @param int $expiryMinutes OTP validity period in minutes
     * @param string $userName Optional user name for personalization
     */
    public function sendOTP($email, $otp, $expiryMinutes = 10, $userName = '')
    {
        try {
            // Log to console in development mode
            if ($this->logEmails) {
                $this->logToConsole($email, $otp, $userName);
            }

            // In development mode, only log (don't actually send)
            if ($this->developmentMode && !$this->shouldSendInDev()) {
                error_log("📧 [DEV MODE] Email not sent. Check console output above.");
                return true;
            }

            // Reset recipients
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset OTP - ISKOLE';
            $this->mailer->Body = $this->getOTPEmailTemplate($otp, $expiryMinutes, $userName);
            $this->mailer->AltBody = $this->getOTPPlainText($otp, $expiryMinutes, $userName);

            // Send email
            $result = $this->mailer->send();

            if ($result) {
                error_log("✅ OTP email sent successfully to: $email");
            }

            return $result;

        } catch (Exception $e) {
            error_log("❌ Email Error: " . $this->mailer->ErrorInfo);
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Log OTP to console for development
     */
    private function logToConsole($email, $otp, $userName = '')
    {
        $border = str_repeat('=', 60);
        $message = "\n" . $border . "\n";
        $message .= "🔐 PASSWORD RESET OTP\n";
        $message .= $border . "\n";
        if ($userName) {
            $message .= "👤 User: $userName\n";
        }
        $message .= "📧 Email: $email\n";
        $message .= "🔑 OTP Code: $otp\n";
        $message .= "⏰ Valid for: 10 minutes\n";
        $message .= "🕐 Generated: " . date('Y-m-d H:i:s') . "\n";
        $message .= $border . "\n";

        // Log to error log (visible in terminal)
        error_log($message);

        // Also write to a separate OTP log file for easy access
        $logFile = __DIR__ . '/../../storage/logs/otp.log';
        $logDir = dirname($logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($logFile, $message, FILE_APPEND);
    }

    /**
     * Check if emails should be sent in development mode
     */
    private function shouldSendInDev()
    {
        // Check config file first, then environment variable
        if (isset($this->config['send_in_dev']) && $this->config['send_in_dev'] === true) {
            return true;
        }
        return getenv('SEND_EMAILS_IN_DEV') === 'true';
    }

    /**
     * HTML email template for OTP
     */
    private function getOTPEmailTemplate($otp, $expiryMinutes, $userName = '')
    {
        // Load template from file
        $templatePath = __DIR__ . '/../../public/assets/email-templates/otp-template.html';

        if (file_exists($templatePath)) {
            $template = file_get_contents($templatePath);

            // Replace placeholders
            $replacements = [
                '{{OTP_CODE}}' => $otp,
                '{{EXPIRY_MINUTES}}' => $expiryMinutes,
                '{{USER_NAME}}' => $userName ?: 'User',
                '{{CURRENT_YEAR}}' => date('Y')
            ];

            return str_replace(array_keys($replacements), array_values($replacements), $template);
        }

        // Fallback to inline template if file not found
        return $this->getOTPEmailTemplateFallback($otp, $expiryMinutes);
    }

    /**
     * Fallback HTML email template for OTP (if template file is missing)
     */
    private function getOTPEmailTemplateFallback($otp, $expiryMinutes)
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 8px;
            color: #667eea;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Password Reset Request</h1>
        </div>
        <div class="content">
            <p>Your OTP Code: <strong style="font-size:24px; color:#667eea;">$otp</strong></p>
            <p>Valid for $expiryMinutes minutes</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Plain text version of OTP email
     */
    private function getOTPPlainText($otp, $expiryMinutes, $userName = '')
    {
        // Load template from file
        $templatePath = __DIR__ . '/../../public/assets/email-templates/otp-template.txt';

        if (file_exists($templatePath)) {
            $template = file_get_contents($templatePath);

            // Replace placeholders
            $replacements = [
                '{{OTP_CODE}}' => $otp,
                '{{EXPIRY_MINUTES}}' => $expiryMinutes,
                '{{USER_NAME}}' => $userName ?: 'User',
                '{{CURRENT_YEAR}}' => date('Y')
            ];

            return str_replace(array_keys($replacements), array_values($replacements), $template);
        }

        // Fallback to inline template if file not found
        return $this->getOTPPlainTextFallback($otp, $expiryMinutes);
    }

    /**
     * Fallback plain text version of OTP email
     */
    private function getOTPPlainTextFallback($otp, $expiryMinutes)
    {
        return <<<TEXT
ISKOLE - Password Reset Request

Hello,

You have requested to reset your password for your ISKOLE account.

Your OTP Code: $otp
Valid for: $expiryMinutes minutes

What to do next:
1. Return to the password reset page
2. Enter the 6-digit OTP code shown above
3. Create your new password

SECURITY NOTICE: Do not share this code with anyone. Our team will never ask for your OTP.

If you didn't request this password reset, please ignore this email and ensure your account is secure.

Best regards,
ISKOLE Team

---
This is an automated message. Please do not reply to this email.
© 2026 ISKOLE School Management System. All rights reserved.
TEXT;
    }

    /**
     * Send test email to verify configuration
     */
    public function sendTestEmail($email)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'ISKOLE Email Configuration Test';
            $this->mailer->Body = '<h1>Email Configuration Successful!</h1><p>Your SMTP settings are working correctly.</p>';
            $this->mailer->AltBody = 'Email Configuration Successful! Your SMTP settings are working correctly.';

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Test Email Error: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function loadConfig()
    {
        try {
            $configFile = __DIR__ . '/../Config/email.php';
            if (!file_exists($configFile)) {
                return [];
            }

            $config = require $configFile;

            if (!is_array($config)) {
                throw new Exception('Invalid email config format.');
            }

            return $config;
        } catch (Throwable $e) {
            // Optionally log $e->getMessage()
            return [];
        }
    }
}
