# Password Reset Feature Documentation

## Overview

The password reset feature allows users to securely reset their passwords through a multi-step process involving email verification.

## Files Created/Modified

### Views

1. **`/app/Views/login/resetPassword.php`** - Password reset request form
2. **`/app/Views/login/resetPasswordIndex.php`** - Wrapper for reset password form
3. **`/app/Views/login/setNewPassword.php`** - New password entry form
4. **`/app/Views/login/setNewPasswordIndex.php`** - Wrapper for new password form
5. **`/app/Views/login/login.php`** - Updated with "Forgot Password?" link

### Controllers

- **`/app/Controllers/LoginController.php`** - Added two new methods:
  - `resetPassword()` - Handles password reset requests
  - `setNewPassword()` - Handles setting new password with token

### Styles

- **`/public/css/login/login.css`** - Added:
  - `.success` class for success messages
  - Link hover effects

## URL Routes

### Available Routes:

1. **Login Page**

   - URL: `/login`
   - Method: GET
   - Shows login form with "Forgot Password?" link

2. **Request Password Reset**

   - URL: `/login/resetPassword`
   - Method: GET - Shows reset password form
   - Method: POST - Processes reset request
   - Form Field: `email`

3. **Set New Password**
   - URL: `/login/setNewPassword?token={reset_token}`
   - Method: GET - Shows new password form
   - Method: POST - Processes password update
   - Form Fields: `token`, `password`, `confirm_password`

## User Flow

```
┌─────────────────┐
│   Login Page    │
└────────┬────────┘
         │ Click "Forgot Password?"
         ▼
┌─────────────────┐
│ Reset Password  │ ← Enter email
│     Form        │
└────────┬────────┘
         │ Submit
         ▼
┌─────────────────┐
│ Success Message │ ← "Check your email"
└────────┬────────┘
         │
         │ (User receives email with link)
         │
         ▼
┌─────────────────┐
│ Set New Password│ ← Enter new password
│     Form        │   (with token in URL)
└────────┬────────┘
         │ Submit
         ▼
┌─────────────────┐
│ Success Message │ ← "Password reset!"
│  + Login Link   │
└─────────────────┘
```

## Features

### Security Features

- Email validation
- Password length validation (minimum 8 characters)
- Password confirmation matching
- Token-based reset (prevents unauthorized resets)
- No email enumeration (same message whether email exists or not)

### UI Features

- Glassmorphism design matching login page
- Responsive design (mobile-friendly)
- Animated success/error messages
- Smooth transitions
- "Back to Login" links on all pages
- Login box positioned on the right side

### Error Handling

- Empty field validation
- Invalid email format detection
- Password mismatch detection
- Password length validation
- Token validation
- User-friendly error messages

## TODO: Production Implementation

The current implementation is a **complete frontend with backend structure**, but requires the following for production:

### 1. Database Changes

Add a password reset tokens table:

```sql
CREATE TABLE password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires)
);
```

### 2. UserModel Updates

Add these methods to `/app/Model/UserModel.php`:

```php
public function storeResetToken($userID, $token, $expires) {
    $sql = "INSERT INTO password_reset_tokens (userID, token, expires)
            VALUES (?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$userID, $token, $expires]);
}

public function validateResetToken($token) {
    $sql = "SELECT * FROM password_reset_tokens
            WHERE token = ? AND expires > ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$token, time()]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updatePassword($userID, $hashedPassword) {
    $sql = "UPDATE users SET password = ? WHERE userID = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$hashedPassword, $userID]);
}

public function deleteResetToken($token) {
    $sql = "DELETE FROM password_reset_tokens WHERE token = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$token]);
}

public function cleanExpiredTokens() {
    $sql = "DELETE FROM password_reset_tokens WHERE expires < ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([time()]);
}
```

### 3. Email Configuration

Configure email sending in LoginController:

```php
private function sendResetEmail($email, $token) {
    $resetLink = "https://yourdomain.com/login/setNewPassword?token=" . $token;

    $subject = "Password Reset Request - ISKOLE";
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .button { background: #667eea; color: white; padding: 12px 24px;
                      text-decoration: none; border-radius: 8px; display: inline-block; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Password Reset Request</h2>
            <p>You requested to reset your password. Click the button below to proceed:</p>
            <p><a href='{$resetLink}' class='button'>Reset Password</a></p>
            <p>Or copy this link: {$resetLink}</p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@iskole.com" . "\r\n";

    return mail($email, $subject, $message, $headers);
}
```

### 4. Update LoginController Methods

Replace the TODO comments in:

- `resetPassword()` method (lines with token generation)
- `setNewPassword()` method (lines with token validation)

## Testing

### Test Reset Password Request:

1. Navigate to: `http://localhost/login`
2. Click "Forgot Password?"
3. Enter email: `test@example.com`
4. Click "Send Reset Link"
5. Should see success message

### Test Set New Password:

1. Navigate to: `http://localhost/login/setNewPassword?token=test123`
2. Enter new password (min 8 chars)
3. Confirm password
4. Click "Reset Password"
5. Should see success message

### Test Validation:

- Try empty email → Should show error
- Try invalid email format → Should show error
- Try passwords that don't match → Should show error
- Try password < 8 characters → Should show error

## Styling Classes

### Success Message

```css
.success {
  background: linear-gradient(135deg, #26de81 0%, #20bf6b 100%);
  color: white;
  /* ... with checkmark icon */
}
```

### Error Message (already exists)

```css
.error {
  background: linear-gradient(135deg, #fc5c65 0%, #eb3b5a 100%);
  color: white;
  /* ... with warning icon */
}
```

## Configuration Notes

- **Token Expiration**: Currently set to 1 hour (3600 seconds)
- **Password Requirements**: Minimum 8 characters
- **Email Security**: Same message shown whether email exists or not
- **Session Security**: No session required for public reset pages

## Maintenance

### Periodic Cleanup

Run this periodically to clean expired tokens:

```php
// In a cron job or scheduled task
$userModel = new UserModel();
$userModel->cleanExpiredTokens();
```

## Support

For issues or questions, refer to:

- Main documentation: `DOCUMENTATION-INDEX.md`
- Troubleshooting: `TROUBLESHOOTING.md`
- Routing guide: `ROUTING-GUIDE.md`
