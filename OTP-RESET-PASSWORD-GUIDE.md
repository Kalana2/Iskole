# OTP-Based Password Reset Guide

## Overview

The password reset system now uses a Two-Factor Authentication (OTP) approach for enhanced security:

1. **Step 1**: User enters email → System sends 6-digit OTP
2. **Step 2**: User enters OTP → System verifies and grants access to password reset
3. **Step 3**: User sets new password → Password is updated in database

## Features

### Security Features

- ✅ **6-digit OTP** (100,000 - 999,999)
- ✅ **10-minute OTP expiry** (configurable)
- ✅ **30-minute reset token expiry** after OTP verification
- ✅ **Session-based validation** (no database storage needed initially)
- ✅ **OTP resend functionality**
- ✅ **Email verification** before sending OTP
- ✅ **Password strength requirement** (minimum 8 characters)
- ✅ **Password confirmation** validation

### User Experience

- ✅ Beautiful glassmorphism UI matching login page
- ✅ Dynamic form transitions (email → OTP → new password)
- ✅ Real-time validation and error messages
- ✅ Success/error notifications with animations
- ✅ Auto-focus on OTP input field
- ✅ Resend OTP button (AJAX-based)
- ✅ Back to login link on all pages

## File Structure

```
app/
├── Controllers/
│   └── LoginController.php         # resetPassword(), setNewPassword(), generateOTP()
├── Model/
│   └── UserModel.php               # updatePassword()
└── Views/
    └── login/
        ├── resetPassword.php        # OTP request/verification form
        ├── resetPasswordIndex.php   # Wrapper for resetPassword
        ├── setNewPassword.php       # New password form
        └── setNewPasswordIndex.php  # Wrapper for setNewPassword

public/
└── css/
    └── login/
        └── login.css               # Shared styles (includes .success class)
```

## Flow Diagram

```
┌─────────────────┐
│ User clicks     │
│ "Forgot Pass?"  │
└────────┬────────┘
         │
         v
┌─────────────────┐
│ Enter Email     │
│ Click "Send OTP"│
└────────┬────────┘
         │
         v
┌──────────────────┐
│ OTP Generated    │
│ (6 digits)       │
│ Stored in session│
│ Expiry: 10 min   │
└────────┬─────────┘
         │
         v
┌─────────────────┐
│ Enter OTP       │
│ Click "Verify"  │
└────────┬────────┘
         │
    ┌────┴────┐
    │ Valid?  │
    └─┬────┬──┘
      │No  │Yes
      │    └──────────┐
      v               v
┌─────────┐    ┌──────────────┐
│ Error   │    │ Generate     │
│ Message │    │ Reset Token  │
└─────────┘    │ (session)    │
               │ Expiry: 30min│
               └──────┬───────┘
                      │
                      v
               ┌──────────────┐
               │ Set New      │
               │ Password     │
               └──────┬───────┘
                      │
                      v
               ┌──────────────┐
               │ Password     │
               │ Updated in DB│
               └──────┬───────┘
                      │
                      v
               ┌──────────────┐
               │ Redirect to  │
               │ Login with   │
               │ Success Msg  │
               └──────────────┘
```

## Usage Guide

### For Users

1. **Navigate to Password Reset**

   - Go to `/login`
   - Click "Forgot Password?" link

2. **Request OTP**

   - Enter your registered email address
   - Click "Send OTP" button
   - OTP will be sent to your email (currently logged in terminal for dev)

3. **Verify OTP**

   - Enter the 6-digit OTP from your email
   - Click "Verify OTP"
   - If OTP is incorrect or expired, you can request a new one

4. **Set New Password**

   - Enter your new password (minimum 8 characters)
   - Confirm your new password
   - Click "Reset Password"

5. **Login**
   - You'll be redirected to login page
   - Use your new password to login

### For Developers

#### Routes

- `GET /login/resetPassword` - Show email input form
- `POST /login/resetPassword` (action=send_otp) - Generate and send OTP
- `POST /login/resetPassword` (action=verify_otp) - Verify OTP
- `POST /login/resetPassword` (action=resend_otp) - Resend OTP (AJAX)
- `GET /login/setNewPassword?token=xxx` - Show new password form
- `POST /login/setNewPassword` - Update password

#### Session Variables

**During OTP Process:**

```php
$_SESSION['reset_otp']         // The 6-digit OTP
$_SESSION['reset_email']       // User's email
$_SESSION['otp_expiry']        // Unix timestamp (now + 600 seconds)
```

**After OTP Verification:**

```php
$_SESSION['reset_token']       // Secure reset token
$_SESSION['reset_token_email'] // User's email
$_SESSION['reset_token_expiry']// Unix timestamp (now + 1800 seconds)
```

**After Password Reset:**

```php
$_SESSION['password_reset_success'] // Boolean flag for login page
```

#### Controller Methods

```php
// LoginController.php

// Main reset password handler
public function resetPassword()
{
    // Handles:
    // - GET: Show form
    // - POST action=send_otp: Generate OTP
    // - POST action=verify_otp: Verify OTP
    // - POST action=resend_otp: Resend OTP (AJAX)
}

// Generate 6-digit OTP
private function generateOTP()
{
    return sprintf("%06d", mt_rand(100000, 999999));
}

// Set new password
public function setNewPassword()
{
    // Handles:
    // - GET: Validate token and show form
    // - POST: Update password in database
}
```

```php
// UserModel.php

// Update user password
public function updatePassword($userId, $hashedPassword)
{
    // Updates password and pwdChanged timestamp
    // Returns true on success, false on failure
}
```

## Configuration

### OTP Expiry Time

Located in `LoginController::resetPassword()`:

```php
$this->session->set('otp_expiry', time() + 600); // 600 seconds = 10 minutes
```

### Reset Token Expiry Time

Located in `LoginController::resetPassword()`:

```php
$this->session->set('reset_token_expiry', time() + 1800); // 1800 seconds = 30 minutes
```

### Password Requirements

Located in `LoginController::setNewPassword()`:

```php
if (strlen($password) < 8) {
    // Error: Password too short
}
```

## Email Integration (TODO)

Currently, OTP is logged to the server error log for development. To enable email sending:

### 1. Create Email Sender Class

```php
// app/Core/EmailSender.php
class EmailSender
{
    public function sendOTP($email, $otp)
    {
        $subject = "Your Password Reset OTP - ISKOLE";
        $message = "Your OTP for password reset is: $otp\n\n";
        $message .= "This OTP will expire in 10 minutes.\n\n";
        $message .= "If you didn't request this, please ignore this email.";

        // Using PHP mail()
        mail($email, $subject, $message);

        // OR using PHPMailer/SMTP
        // $mailer = new PHPMailer();
        // ... configure and send
    }
}
```

### 2. Update LoginController

```php
// In resetPassword() method, after generating OTP:
$emailSender = new EmailSender();
$emailSender->sendOTP($email, $otp);
```

### 3. Recommended Email Libraries

- **PHPMailer** - Most popular, easy to use
- **SwiftMailer** - Feature-rich
- **Symfony Mailer** - Modern, component-based

## Testing

### Development Testing

1. **Check OTP in Logs**

   ```bash
   tail -f /var/log/php_errors.log
   # Or wherever your PHP errors are logged
   ```

2. **Test OTP Expiry**

   - Temporarily reduce expiry time to 30 seconds
   - Request OTP and wait 31 seconds
   - Try to verify - should show expiry error

3. **Test Invalid OTP**

   - Request OTP
   - Enter wrong code
   - Should show error message

4. **Test Resend OTP**
   - Request OTP
   - Click "Resend OTP"
   - Old OTP should be replaced with new one

### Production Testing Checklist

- [ ] Email delivery working
- [ ] OTP arrives within 1 minute
- [ ] OTP format is 6 digits
- [ ] OTP expires after configured time
- [ ] Invalid OTP shows error
- [ ] Expired OTP shows error
- [ ] Resend OTP generates new code
- [ ] Password requirements enforced
- [ ] Password mismatch shows error
- [ ] Successful reset redirects to login
- [ ] Success message appears on login page
- [ ] Can login with new password
- [ ] Old password no longer works

## Security Considerations

### Current Implementation

✅ Session-based OTP storage (server-side)
✅ Time-limited OTP (10 minutes)
✅ Time-limited reset token (30 minutes)
✅ Password hashing with `password_hash()`
✅ Input validation and sanitization
✅ CSRF protection via POST forms
✅ No email enumeration (generic success messages)

### Recommended Enhancements

🔒 **Rate Limiting** - Limit OTP requests per IP/email
🔒 **Database OTP Storage** - For multi-server environments
🔒 **Email Queue** - Asynchronous email sending
🔒 **Audit Logging** - Log all password reset attempts
🔒 **2FA Integration** - Optional two-factor authentication
🔒 **Password History** - Prevent reusing recent passwords
🔒 **Account Lockout** - After X failed OTP attempts

## Troubleshooting

### OTP Not Received

1. Check server error logs for OTP value
2. Verify email address is correct in database
3. Check spam/junk folder
4. Ensure email sending is configured

### "OTP Expired" Error

1. OTP is valid for 10 minutes only
2. Request a new OTP using "Resend OTP" button
3. Complete verification within time limit

### "Invalid OTP" Error

1. Ensure you entered all 6 digits correctly
2. OTP is case-sensitive (if modified)
3. Don't include spaces
4. Use the most recent OTP if multiple were requested

### Password Reset Not Working

1. Check that UserModel::updatePassword() exists
2. Verify database connection
3. Check user has permission to update password
4. Check PHP error logs for exceptions

### Session Issues

1. Ensure session is started in `init.php`
2. Check session cookie settings
3. Verify session storage is writable
4. Check session expiry settings in php.ini

## Future Enhancements

### Planned Features

- [ ] Email template system with branding
- [ ] SMS OTP option
- [ ] Rate limiting per IP and email
- [ ] Admin dashboard for password reset audit log
- [ ] Password strength meter
- [ ] Security questions as backup
- [ ] Remember device option
- [ ] Multi-language support

### Database Schema (Optional)

For persistent OTP storage across multiple servers:

```sql
CREATE TABLE password_reset_otps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    otp VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    verified BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    INDEX idx_email (email),
    INDEX idx_otp (otp),
    INDEX idx_expires (expires_at),
    FOREIGN KEY (user_id) REFERENCES user(userID) ON DELETE CASCADE
);
```

## Support

For issues or questions:

- Check error logs: `/var/log/php_errors.log`
- Review session data: `var_dump($_SESSION);`
- Test email delivery independently
- Verify database user permissions

## License

This implementation is part of the ISKOLE project.

---

**Last Updated**: December 31, 2025
**Version**: 2.0 (OTP-based)
