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
        $this->config = require __DIR__ . '/../Config/email.php';
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
     */
    public function sendOTP($email, $otp, $expiryMinutes = 10)
    {
        try {
            // Log to console in development mode
            if ($this->logEmails) {
                $this->logToConsole($email, $otp);
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
            $this->mailer->Body = $this->getOTPEmailTemplate($otp, $expiryMinutes);
            $this->mailer->AltBody = $this->getOTPPlainText($otp, $expiryMinutes);

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
    private function logToConsole($email, $otp)
    {
        $border = str_repeat('=', 60);
        $message = "\n" . $border . "\n";
        $message .= "🔐 PASSWORD RESET OTP\n";
        $message .= $border . "\n";
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
        // You can set an environment variable to enable actual sending in dev
        return getenv('SEND_EMAILS_IN_DEV') === 'true';
    }

    /**
     * HTML email template for OTP
     */
    private function getOTPEmailTemplate($otp, $expiryMinutes)
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
        .otp-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>You have requested to reset your password for your ISKOLE account. Please use the One-Time Password (OTP) below to proceed:</p>
            
            <div class="otp-box">
                <div class="otp-label">Your OTP Code:</div>
                <div class="otp-code">$otp</div>
                <div class="otp-label">Valid for $expiryMinutes minutes</div>
            </div>
            
            <div class="info-box">
                <p><strong>⚠️ Security Notice:</strong> Do not share this code with anyone. Our team will never ask for your OTP.</p>
            </div>
            
            <p><strong>What to do next:</strong></p>
            <ol>
                <li>Return to the password reset page</li>
                <li>Enter the 6-digit OTP code shown above</li>
                <li>Create your new password</li>
            </ol>
            
            <p>If you didn't request this password reset, please ignore this email and ensure your account is secure.</p>
            
            <p>Best regards,<br>
            <strong>ISKOLE Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; 2025 ISKOLE School Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Plain text version of OTP email
     */
    private function getOTPPlainText($otp, $expiryMinutes)
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
© 2025 ISKOLE School Management System. All rights reserved.
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
}
