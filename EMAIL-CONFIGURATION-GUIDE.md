# Email Configuration Guide for OTP

## 🚀 Quick Start

The OTP password reset feature now uses **PHPMailer** to send emails. Follow these steps to configure email sending.

## 📧 Email Providers Supported

- Gmail
- Outlook/Office 365
- Yahoo Mail
- SendGrid
- Mailgun
- Any SMTP server

## 🔧 Configuration Steps

### 1. Edit Email Configuration

Open `app/Config/email.php` and update the following:

```php
return [
    'smtp_host' => 'smtp.gmail.com',          // Your SMTP server
    'smtp_port' => 587,                        // Port (587 for TLS, 465 for SSL)
    'smtp_secure' => 'tls',                    // 'tls' or 'ssl'
    'smtp_username' => 'your-email@gmail.com', // Your email
    'smtp_password' => 'your-app-password',    // Your app password
    'from_email' => 'noreply@iskole.com',      // From address
    'from_name' => 'ISKOLE',                   // From name
    'development_mode' => true,                 // false for production
    'log_emails' => true,                       // Console logging
];
```

### 2. Provider-Specific Settings

#### Gmail

1. **Enable 2-Factor Authentication** on your Google Account
2. **Generate App Password**:

   - Go to [Google Account Security](https://myaccount.google.com/security)
   - Select "2-Step Verification"
   - Scroll to "App passwords"
   - Generate a new app password
   - Copy the 16-character password

3. **Update Configuration**:

```php
'smtp_host' => 'smtp.gmail.com',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_username' => 'your-email@gmail.com',
'smtp_password' => 'your-16-char-app-password',  // NO SPACES!
```

#### Outlook/Office 365

```php
'smtp_host' => 'smtp-mail.outlook.com',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_username' => 'your-email@outlook.com',
'smtp_password' => 'your-password',
```

#### Yahoo Mail

```php
'smtp_host' => 'smtp.mail.yahoo.com',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_username' => 'your-email@yahoo.com',
'smtp_password' => 'your-app-password',  // Generate at Yahoo
```

#### SendGrid

```php
'smtp_host' => 'smtp.sendgrid.net',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_username' => 'apikey',
'smtp_password' => 'your-sendgrid-api-key',
```

#### Custom SMTP Server

```php
'smtp_host' => 'mail.yourdomain.com',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_username' => 'no-reply@yourdomain.com',
'smtp_password' => 'your-smtp-password',
```

## 🧪 Testing Email Configuration

### Method 1: Using the Test Script

Create a test file: `public/test_email.php`

```php
<?php
require_once '../app/Core/EmailService.php';

$emailService = new EmailService();
$testEmail = 'your-test-email@example.com'; // Change this!

if ($emailService->sendTestEmail($testEmail)) {
    echo "✅ Test email sent successfully! Check your inbox.";
} else {
    echo "❌ Failed to send test email. Check error logs.";
}
```

Run: `http://localhost/test_email.php`

### Method 2: Via Terminal

```bash
cd /home/snake/Projects/Iskole
php -r "
require_once 'app/Core/EmailService.php';
\$email = new EmailService();
\$result = \$email->sendTestEmail('your-email@example.com');
echo \$result ? '✅ Success' : '❌ Failed';
"
```

## 📝 Development Mode vs Production

### Development Mode (Default)

```php
'development_mode' => true,
'log_emails' => true,
```

**Behavior:**

- ✅ OTP is logged to console/terminal
- ✅ OTP is saved to `storage/logs/otp.log`
- ❌ Email is NOT actually sent (unless SEND_EMAILS_IN_DEV=true)
- ✅ Perfect for testing without spamming inboxes

**Console Output:**

```
============================================================
🔐 PASSWORD RESET OTP
============================================================
📧 Email: user@example.com
🔑 OTP Code: 123456
⏰ Valid for: 10 minutes
🕐 Generated: 2025-12-31 10:30:45
============================================================
```

**Check OTP Log File:**

```bash
tail -f storage/logs/otp.log
```

### Production Mode

```php
'development_mode' => false,
'log_emails' => false,  // Optional: disable for security
```

**Behavior:**

- ✅ Email is sent via SMTP
- ❌ OTP is NOT logged to console (security)
- ✅ Only errors are logged

## 🔍 Viewing OTP in Development

### Option 1: Terminal/Console Log

When you request an OTP, check your terminal where PHP is running:

```bash
# If using PHP built-in server
php -S localhost:8000

# If using Apache, check error log
tail -f /var/log/apache2/error.log

# If using Docker
docker logs -f iskole_web
```

### Option 2: OTP Log File

```bash
# Watch the OTP log in real-time
tail -f storage/logs/otp.log

# View last 50 OTPs
tail -n 50 storage/logs/otp.log

# Search for specific email
grep "user@example.com" storage/logs/otp.log
```

### Option 3: Enable Debug Mode

In `app/Config/email.php`:

```php
'debug' => 2,  // 0=off, 1=client, 2=server, 3=connection
```

This will show detailed SMTP conversation in logs.

## 🔐 Security Best Practices

### 1. Use Environment Variables (Recommended)

Instead of hardcoding credentials, use environment variables:

**Create `.env` file:**

```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
FROM_EMAIL=noreply@iskole.com
```

**Update `app/Config/email.php`:**

```php
return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_username' => getenv('SMTP_USERNAME'),
    'smtp_password' => getenv('SMTP_PASSWORD'),
    // ... rest of config
];
```

**Load environment variables in `app/init.php`:**

```php
// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
        }
    }
}
```

### 2. Add `.env` to `.gitignore`

```bash
echo ".env" >> .gitignore
```

### 3. Never Commit Passwords

- ❌ Never commit `app/Config/email.php` with real passwords
- ✅ Use app passwords (not your main password)
- ✅ Rotate credentials regularly
- ✅ Use different credentials for dev/staging/production

## 📊 Monitoring & Logs

### Check if emails are being sent:

```bash
# Check PHP error log
tail -f /var/log/php_errors.log

# Check Apache error log
tail -f /var/log/apache2/error.log

# Check OTP log
tail -f storage/logs/otp.log

# Search for email errors
grep -i "email" /var/log/php_errors.log
```

### Log Rotation

Create `storage/logs/.gitignore`:

```
*
!.gitignore
```

## 🐛 Troubleshooting

### Email Not Sending

**1. Check SMTP credentials:**

```bash
# Test SMTP connection
telnet smtp.gmail.com 587
```

**2. Check PHP error log:**

```bash
tail -f /var/log/php_errors.log
```

**3. Enable debug mode:**

```php
'debug' => 3,  // Maximum verbosity
```

**4. Check firewall:**

```bash
# Allow outgoing SMTP
sudo ufw allow out 587/tcp
sudo ufw allow out 465/tcp
```

### Common Errors

| Error                       | Solution                                   |
| --------------------------- | ------------------------------------------ |
| `SMTP connect() failed`     | Check host/port, firewall                  |
| `Invalid login`             | Verify username/password, use app password |
| `Connection timeout`        | Check firewall, try different port         |
| `TLS required`              | Change `smtp_secure` to `'tls'`            |
| `Certificate verify failed` | Update CA certificates                     |

### Gmail Specific Issues

1. **"Less secure app access"** - Gmail removed this. Use App Password instead.
2. **"Login with your browser"** - Enable 2FA and use App Password
3. **"Too many login attempts"** - Wait 15 minutes, try again

## 🎯 Force Send Emails in Development

To actually send emails in development mode:

```bash
export SEND_EMAILS_IN_DEV=true
```

Or in PHP:

```php
putenv('SEND_EMAILS_IN_DEV=true');
```

## 📬 Email Template Customization

Edit `app/Core/EmailService.php`:

```php
private function getOTPEmailTemplate($otp, $expiryMinutes)
{
    // Customize HTML here
    return <<<HTML
    <!-- Your custom HTML -->
    HTML;
}
```

## ✅ Checklist

- [ ] Install PHPMailer: `composer require phpmailer/phpmailer`
- [ ] Configure `app/Config/email.php`
- [ ] Generate app password for Gmail (if using Gmail)
- [ ] Test email configuration
- [ ] Check OTP appears in console/logs
- [ ] Set `development_mode` to `false` for production
- [ ] Configure environment variables
- [ ] Add `.env` to `.gitignore`
- [ ] Test actual email sending
- [ ] Configure log rotation
- [ ] Monitor email delivery

## 🚀 Production Checklist

Before deploying to production:

- [ ] Set `development_mode` = `false`
- [ ] Set `log_emails` = `false` (or only errors)
- [ ] Set `debug` = `0`
- [ ] Use environment variables for credentials
- [ ] Test from production server
- [ ] Set up email monitoring
- [ ] Configure SPF/DKIM records (if using custom domain)
- [ ] Set up bounce handling
- [ ] Configure rate limiting

## 📖 Additional Resources

- [PHPMailer GitHub](https://github.com/PHPMailer/PHPMailer)
- [Gmail App Passwords](https://support.google.com/accounts/answer/185833)
- [SendGrid Documentation](https://docs.sendgrid.com/)
- [Email Deliverability Best Practices](https://mailtrap.io/blog/email-deliverability/)

---

**Need Help?** Check the logs:

```bash
# OTP Log
tail -f storage/logs/otp.log

# Error Log
tail -f /var/log/php_errors.log
```
