# Email Setup Checklist

## ✅ Completed

- [x] PHPMailer installed via Composer
- [x] EmailService class created
- [x] Email configuration file created
- [x] Development mode with logging enabled
- [x] `send_in_dev` flag added to force email sending in dev mode
- [x] OTP logging to console and file working
- [x] Test scripts created

## 📋 TODO: Configure Gmail Credentials

### 1. Get Gmail App Password

- [ ] Go to https://myaccount.google.com/apppasswords
- [ ] Enable 2-Factor Authentication (if not already enabled)
- [ ] Generate new App Password for "Mail" → "ISKOLE System"
- [ ] Copy the 16-character password

### 2. Update Configuration

- [ ] Open `app/Config/email.php`
- [ ] Replace `'smtp_username' => 'your-email@gmail.com'` with your actual Gmail
- [ ] Replace `'smtp_password' => 'your-app-password'` with the App Password from step 1

### 3. Test Email Configuration

Run the test script:

```bash
php scripts/test-email-config.php
```

Expected output:

- ✅ Configuration check passes
- ✅ SMTP connection successful
- ✅ Test email sent (optional)

### 4. Test OTP Password Reset

1. Navigate to: http://localhost/iskole/public/login/resetPassword
2. Enter email: iskole.2y@gmail.com (or your Gmail)
3. Click "Send OTP"
4. Check terminal for SMTP debug output
5. Check email inbox for OTP
6. Enter OTP and set new password

## 🔍 Debugging Tips

### Check Logs

```bash
# View OTP logs
tail -f storage/logs/otp.log

# View PHP error log (location varies by server)
tail -f /var/log/apache2/error.log
# OR
tail -f /var/log/php-fpm/error.log
```

### Common Issues

**"Invalid credentials" error**

- Using regular Gmail password instead of App Password
- App Password has spaces (try removing them)

**"SMTP connection failed"**

- Port 587 blocked by firewall
- Try switching to port 465 with SSL:
  ```php
  'smtp_port' => 465,
  'smtp_secure' => 'ssl',
  ```

**Email not sending in dev mode**

- Make sure `send_in_dev => true` in config
- OR set env variable: `export SEND_EMAILS_IN_DEV=true`

**Email goes to spam**

- Normal for development/testing
- Check Spam/Junk folder
- Gmail may flag first few emails as suspicious

## 📚 Documentation Files

- `GMAIL-APP-PASSWORD-SETUP.md` - Detailed Gmail setup instructions
- `EMAIL-CONFIGURATION-GUIDE.md` - Complete email system documentation
- `scripts/test-email-config.php` - Email configuration test script

## 🚀 Production Deployment

When moving to production:

- [ ] Set `development_mode => false`
- [ ] Set `log_emails => false` (or keep for monitoring)
- [ ] Use production email credentials
- [ ] Consider using dedicated email service (SendGrid, Mailgun, AWS SES)
- [ ] Configure SPF/DKIM records for domain
