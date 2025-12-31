#!/usr/bin/env php
<?php
/**
 * Email Configuration Test Script
 * 
 * Tests if PHPMailer can connect to Gmail SMTP and send emails
 */

// Load composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load email config
$config = require __DIR__ . '/../app/Config/email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Colors for terminal output
$colors = [
    'green' => "\033[32m",
    'red' => "\033[31m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'reset' => "\033[0m"
];

function colorize($text, $color, $colors)
{
    return $colors[$color] . $text . $colors['reset'];
}

echo colorize("\n========================================\n", 'blue', $colors);
echo colorize("  ISKOLE Email Configuration Test\n", 'blue', $colors);
echo colorize("========================================\n\n", 'blue', $colors);

// Step 1: Check configuration
echo colorize("📋 Step 1: Checking Configuration...\n", 'yellow', $colors);
echo "  SMTP Host: " . $config['smtp_host'] . "\n";
echo "  SMTP Port: " . $config['smtp_port'] . "\n";
echo "  SMTP Secure: " . $config['smtp_secure'] . "\n";
echo "  Username: " . $config['smtp_username'] . "\n";
echo "  Password: " . (strlen($config['smtp_password']) > 5 ? str_repeat('*', strlen($config['smtp_password'])) : colorize('NOT SET!', 'red', $colors)) . "\n";
echo "  Development Mode: " . ($config['development_mode'] ? colorize('YES', 'yellow', $colors) : 'NO') . "\n";
echo "  Send in Dev: " . (isset($config['send_in_dev']) && $config['send_in_dev'] ? colorize('YES', 'green', $colors) : colorize('NO', 'red', $colors)) . "\n\n";

// Check if credentials are set
if (
    $config['smtp_username'] === 'your-email@gmail.com' ||
    $config['smtp_password'] === 'your-app-password'
) {
    echo colorize("❌ ERROR: Email credentials not configured!\n", 'red', $colors);
    echo colorize("   Please update app/Config/email.php with your Gmail credentials.\n", 'yellow', $colors);
    echo colorize("   See GMAIL-APP-PASSWORD-SETUP.md for instructions.\n\n", 'yellow', $colors);
    exit(1);
}

// Step 2: Test SMTP connection
echo colorize("🔌 Step 2: Testing SMTP Connection...\n", 'yellow', $colors);

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // Show connection output only
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = $config['smtp_auth'];
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_secure'];
    $mail->Port = $config['smtp_port'];

    // Test connection
    $mail->smtpConnect();
    echo colorize("\n✅ SMTP Connection: SUCCESS\n\n", 'green', $colors);
    $mail->smtpClose();

} catch (Exception $e) {
    echo colorize("\n❌ SMTP Connection: FAILED\n", 'red', $colors);
    echo colorize("   Error: " . $mail->ErrorInfo . "\n\n", 'red', $colors);
    exit(1);
}

// Step 3: Ask to send test email
echo colorize("📧 Step 3: Send Test Email?\n", 'yellow', $colors);
echo "Enter recipient email (or press Enter to skip): ";
$recipient = trim(fgets(STDIN));

if (empty($recipient)) {
    echo colorize("\nTest completed. SMTP configuration is working!\n", 'green', $colors);
    echo colorize("========================================\n\n", 'blue', $colors);
    exit(0);
}

// Send test email
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->clearAddresses();
    $mail->addAddress($recipient);
    $mail->setFrom($config['from_email'], $config['from_name']);

    $mail->isHTML(true);
    $mail->Subject = 'ISKOLE Email Test';
    $mail->Body = '<h1>Email Test Successful!</h1><p>Your ISKOLE email configuration is working correctly.</p>';
    $mail->AltBody = 'Email Test Successful! Your ISKOLE email configuration is working correctly.';

    $mail->send();
    echo colorize("\n✅ Test Email: SENT SUCCESSFULLY\n", 'green', $colors);
    echo colorize("   Check inbox for: $recipient\n\n", 'green', $colors);

} catch (Exception $e) {
    echo colorize("\n❌ Test Email: FAILED\n", 'red', $colors);
    echo colorize("   Error: " . $mail->ErrorInfo . "\n\n", 'red', $colors);
    exit(1);
}

echo colorize("========================================\n", 'blue', $colors);
echo colorize("✅ All tests passed!\n", 'green', $colors);
echo colorize("========================================\n\n", 'blue', $colors);
