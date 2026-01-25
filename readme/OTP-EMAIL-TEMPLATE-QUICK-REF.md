# 📧 OTP Email Template - Quick Reference Card

## 🎯 Quick Access

| Resource          | URL/Path                                           |
| ----------------- | -------------------------------------------------- |
| **Preview Tool**  | `http://localhost:8083/preview-email-template.php` |
| **HTML Template** | `public/assets/email-templates/otp-template.html`  |
| **Text Template** | `public/assets/email-templates/otp-template.txt`   |
| **Documentation** | `public/assets/email-templates/README.md`          |

## 💻 Code Usage

### Send OTP Email

```php
$emailService = new EmailService();
$emailService->sendOTP('user@example.com', '123456');
```

### With User Name

```php
$emailService->sendOTP('user@example.com', '123456', 10, 'John Doe');
```

## 🎨 Customization

### Change Colors

Edit: `public/assets/email-templates/otp-template.html`

```css
/* Line ~40 - Header gradient */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Add Placeholder

1. In template: `{{NEW_PLACEHOLDER}}`
2. In EmailService.php:

```php
$replacements = [
    // ...existing...
    '{{NEW_PLACEHOLDER}}' => $value
];
```

## 🧪 Testing

### 1. Preview in Browser

```
http://localhost:8083/preview-email-template.php
```

### 2. Send Test Email

- Open preview tool
- Click "Send Test Email"
- Enter your email
- Click "Send Now"

### 3. Check Logs (Development Mode)

```bash
tail -f storage/logs/otp.log
```

## 📝 Available Placeholders

| Placeholder          | Type    | Example    |
| -------------------- | ------- | ---------- |
| `{{OTP_CODE}}`       | string  | `123456`   |
| `{{EXPIRY_MINUTES}}` | integer | `10`       |
| `{{USER_NAME}}`      | string  | `John Doe` |
| `{{CURRENT_YEAR}}`   | integer | `2026`     |

## 🔧 File Structure

```
public/
├── preview-email-template.php    # Preview tool
└── assets/
    └── email-templates/
        ├── otp-template.html     # HTML template
        ├── otp-template.txt      # Plain text template
        └── README.md             # Documentation

app/
└── Core/
    └── EmailService.php          # Email service (updated)
```

## ⚡ Quick Edits

### Change Expiry Default

EmailService.php, line ~65:

```php
public function sendOTP($email, $otp, $expiryMinutes = 10, $userName = '')
//                                              ^^^ Change this
```

### Change Subject Line

EmailService.php, line ~85:

```php
$this->mailer->Subject = 'Password Reset OTP - ISKOLE';
//                       ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ Change this
```

### Modify Email Footer

Edit: `public/assets/email-templates/otp-template.html`

```html
<!-- Around line 220 -->
<div class="footer">
  <p>Your custom footer text here</p>
</div>
```

## 🚨 Troubleshooting

### Template Not Loading?

Check file path:

```bash
ls -la public/assets/email-templates/
```

### Preview Not Working?

Check PHP errors:

```bash
docker-compose logs web
```

### Email Not Sending?

1. Check email config: `app/Config/email.php`
2. Review logs: `storage/logs/otp.log`
3. Test SMTP: `scripts/test_email_config.sh`

## 📚 Related Documentation

- Full Summary: `OTP-EMAIL-TEMPLATE-SUMMARY.md`
- Email Setup: `EMAIL-CONFIGURATION-GUIDE.md`
- OTP Guide: `OTP-QUICK-START.md`
- Template Docs: `public/assets/email-templates/README.md`

---

**💡 Pro Tips:**

- Use the preview tool to test changes instantly
- Templates update automatically (no restart needed)
- Placeholders are case-sensitive
- Always maintain both HTML and TXT versions
- Test in multiple email clients before production

---

**Last Updated**: January 1, 2026
