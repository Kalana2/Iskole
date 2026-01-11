# Email Templates - ISKOLE

This directory contains email templates used by the ISKOLE School Management System.

## 📁 Template Files

### 1. OTP Password Reset Templates

#### `otp-template.html`

Beautiful HTML email template for OTP password reset emails.

**Features:**

- Responsive design
- Modern gradient styling
- Clear OTP code display
- Security warnings
- Step-by-step instructions
- Professional branding

**Placeholders:**

- `{{OTP_CODE}}` - The 6-digit OTP code
- `{{EXPIRY_MINUTES}}` - OTP validity period (default: 10 minutes)
- `{{USER_NAME}}` - Recipient's name (optional)
- `{{CURRENT_YEAR}}` - Current year for copyright

#### `otp-template.txt`

Plain text version of the OTP email for email clients that don't support HTML.

**Placeholders:** Same as HTML template

## 🎨 Customization

### Modifying Templates

1. **Edit HTML Template** (`otp-template.html`):

   - Update colors in the `<style>` section
   - Modify layout and structure
   - Add/remove sections
   - Change fonts and typography

2. **Edit Text Template** (`otp-template.txt`):
   - Update text content
   - Modify formatting
   - Add/remove sections

### Color Scheme

The current color scheme uses:

- **Primary Gradient**: `#667eea` to `#764ba2` (Purple gradient)
- **Warning**: `#ffc107` (Yellow)
- **Text**: `#333333` (Dark gray)
- **Secondary Text**: `#555555`, `#888888` (Gray shades)
- **Background**: `#f5f7fa` (Light gray-blue)

### Adding New Placeholders

1. Add placeholder in template file: `{{PLACEHOLDER_NAME}}`
2. Update `EmailService.php` in the `$replacements` array:

```php
$replacements = [
    '{{OTP_CODE}}' => $otp,
    '{{EXPIRY_MINUTES}}' => $expiryMinutes,
    '{{USER_NAME}}' => $userName,
    '{{CURRENT_YEAR}}' => date('Y'),
    '{{NEW_PLACEHOLDER}}' => $value  // Add your new placeholder
];
```

## 🔧 Usage in Code

The templates are automatically loaded by the `EmailService` class:

```php
// Basic usage
$emailService = new EmailService();
$emailService->sendOTP('user@example.com', '123456');

// With user name (optional)
$emailService->sendOTP('user@example.com', '123456', 10, 'John Doe');
```

## 📝 Template Variables

| Variable             | Type    | Required | Description                           |
| -------------------- | ------- | -------- | ------------------------------------- |
| `{{OTP_CODE}}`       | string  | Yes      | The 6-digit OTP code                  |
| `{{EXPIRY_MINUTES}}` | integer | Yes      | Minutes until OTP expires             |
| `{{USER_NAME}}`      | string  | No       | Recipient's name (defaults to "User") |
| `{{CURRENT_YEAR}}`   | integer | Yes      | Current year for copyright            |

## 🎯 Best Practices

1. **Keep it Simple**: Don't overcomplicate the design
2. **Mobile First**: Ensure templates are responsive
3. **Clear CTA**: Make the OTP code prominent and easy to copy
4. **Security First**: Always include security warnings
5. **Fallback**: Always provide a plain text version
6. **Test Thoroughly**: Test in multiple email clients

## 🧪 Testing Templates

### Preview in Browser

1. Open the HTML template directly in a browser:

   ```
   http://localhost:8083/assets/email-templates/otp-template.html
   ```

2. Manually replace placeholders to preview:
   - Replace `{{OTP_CODE}}` with `123456`
   - Replace `{{EXPIRY_MINUTES}}` with `10`
   - Replace `{{USER_NAME}}` with `John Doe`
   - Replace `{{CURRENT_YEAR}}` with current year

### Test Email Sending

```php
// Create test script: test_email_template.php
require_once __DIR__ . '/../app/Core/EmailService.php';

$emailService = new EmailService();
$result = $emailService->sendOTP('your-email@example.com', '123456', 10, 'Test User');

if ($result) {
    echo "✅ Test email sent successfully!";
} else {
    echo "❌ Failed to send test email.";
}
```

## 📧 Email Clients Tested

The templates have been designed to work with:

- ✅ Gmail (Web, iOS, Android)
- ✅ Outlook (Web, Desktop)
- ✅ Apple Mail (macOS, iOS)
- ✅ Yahoo Mail
- ✅ Mozilla Thunderbird
- ✅ Mobile clients (iOS Mail, Android Gmail)

## 🔄 Fallback System

If the template files are missing or cannot be loaded, the `EmailService` class will use built-in fallback templates. This ensures emails are always sent, even if template files are unavailable.

## 📂 File Structure

```
public/assets/email-templates/
├── README.md                 # This file
├── otp-template.html        # HTML OTP email template
└── otp-template.txt         # Plain text OTP email template
```

## 🚀 Adding New Templates

To add a new email template:

1. Create HTML and TXT files in this directory
2. Use placeholder syntax: `{{PLACEHOLDER_NAME}}`
3. Add a new method in `EmailService.php`:

```php
public function sendWelcomeEmail($email, $userName) {
    $template = $this->loadTemplate('welcome-template.html');
    // Replace placeholders and send
}
```

## 📞 Support

For issues or questions about email templates:

- Check the main documentation: `EMAIL-CONFIGURATION-GUIDE.md`
- Review PHPMailer logs in `storage/logs/`
- Check development console output

---

**Last Updated**: January 2026  
**Version**: 1.0.0
