# Gmail App Password Setup Guide

## Quick Steps to Get Your Gmail App Password

### Step 1: Enable 2-Factor Authentication

1. Go to [Google Account Security](https://myaccount.google.com/security)
2. Click on **"2-Step Verification"**
3. Follow the prompts to enable it (if not already enabled)

### Step 2: Generate App Password

1. Go to [App Passwords](https://myaccount.google.com/apppasswords)
2. In the "Select app" dropdown, choose **"Mail"**
3. In the "Select device" dropdown, choose **"Other (Custom name)"**
4. Enter a name like **"ISKOLE System"**
5. Click **"Generate"**
6. **Copy the 16-character password** (it will look like: `xxxx xxxx xxxx xxxx`)

### Step 3: Update Email Configuration

Open `app/Config/email.php` and update:

```php
'smtp_username' => 'iskole.2y@gmail.com',  // Your Gmail address
'smtp_password' => 'xxxx xxxx xxxx xxxx',  // The 16-character app password (spaces optional)
```

## Current Configuration Status

✅ Development mode enabled with `send_in_dev => true`
✅ Debug mode set to 2 (verbose SMTP debugging)
✅ SMTP Host: smtp.gmail.com
✅ Port: 587 (TLS)

## Testing the Email

After updating the password:

1. Go to the password reset page: `http://localhost/iskole/public/login/resetPassword`
2. Enter email: `iskole.2y@gmail.com`
3. Click "Send OTP"
4. Check your terminal for detailed SMTP debug output
5. Check your email inbox for the OTP

## Troubleshooting

### "Invalid credentials" error

- Make sure you're using an **App Password**, not your regular Gmail password
- The app password is 16 characters (spaces are optional)

### "SMTP connection failed"

- Check that port 587 is not blocked by your firewall
- Try port 465 with SSL instead:
  ```php
  'smtp_port' => 465,
  'smtp_secure' => 'ssl',
  ```

### Still in development mode?

- Check that `send_in_dev => true` is set in `app/Config/email.php`
- OR set environment variable: `export SEND_EMAILS_IN_DEV=true`

### Email goes to spam

- This is normal for development/testing
- Check your Spam/Junk folder
- In production, configure SPF/DKIM records for your domain

## Current Email Flow

```
Request OTP → Generate OTP → Log to Console ✅
                    ↓
            EmailService::sendOTP()
                    ↓
    [development_mode = true, send_in_dev = true]
                    ↓
            SMTP Connection to Gmail
                    ↓
            Send Email ✉️ → Inbox
```

## Alternative: Use Mailtrap for Testing

If you don't want to use your real Gmail:

1. Sign up at [Mailtrap.io](https://mailtrap.io) (free)
2. Get SMTP credentials from your inbox
3. Update `app/Config/email.php`:

```php
'smtp_host' => 'sandbox.smtp.mailtrap.io',
'smtp_port' => 587,
'smtp_username' => 'your-mailtrap-username',
'smtp_password' => 'your-mailtrap-password',
```

Mailtrap captures all emails in a test inbox - perfect for development!
