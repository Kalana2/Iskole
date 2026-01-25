# 🚀 QUICK START - View OTP in Console

## ⚡ TL;DR - Get OTP Instantly

When testing password reset, your OTP appears in **3 places**:

### 1️⃣ **Terminal/Console** (Recommended)

Just watch where your web server is running:

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

### 2️⃣ **OTP Log File**

```bash
tail -f storage/logs/otp.log
```

### 3️⃣ **PHP Error Log**

```bash
tail -f /var/log/php_errors.log
```

---

## 🎯 How to Test RIGHT NOW

### Step 1: Start Your Server

```bash
cd /home/snake/Projects/Iskole/public
php -S localhost:8000
```

### Step 2: Open Password Reset

```
http://localhost:8000/login/resetPassword
```

### Step 3: Enter Email & Request OTP

- Type any email from your database
- Click "Send OTP"

### Step 4: Watch Your Terminal

The OTP will appear in a beautiful box! 🎉

### Step 5: Copy & Paste OTP

- Copy the 6-digit code
- Paste into the form
- Create new password

---

## 📋 What You Get

✅ **PHPMailer Installed** - Professional email sending  
✅ **Console Logging** - See OTP instantly in terminal  
✅ **File Logging** - `storage/logs/otp.log`  
✅ **Beautiful Email Templates** - HTML emails ready  
✅ **Development Mode** - No spam during testing  
✅ **Production Ready** - Just configure SMTP

---

## ⚙️ Configuration Files

**Current Settings** (Development Mode):

```php
// app/Config/email.php
'development_mode' => true,   // Logs to console
'log_emails' => true,          // Saves to file
```

**For Production:**

```php
'development_mode' => false,   // Sends real emails
'smtp_username' => 'your-email@gmail.com',
'smtp_password' => 'your-app-password',
```

---

## 🔍 Viewing OTPs

| Method        | Command                                      | When to Use                 |
| ------------- | -------------------------------------------- | --------------------------- |
| **Console**   | (just watch terminal)                        | Best for active development |
| **OTP Log**   | `tail -f storage/logs/otp.log`               | Multiple terminals          |
| **Error Log** | `tail -f /var/log/php_errors.log`            | Debugging                   |
| **Search**    | `grep "user@email.com" storage/logs/otp.log` | Find specific OTP           |

---

## 📖 Full Documentation

- **PHPMAILER-INTEGRATION-COMPLETE.md** - Overview
- **EMAIL-CONFIGURATION-GUIDE.md** - SMTP setup
- **OTP-RESET-PASSWORD-GUIDE.md** - Complete guide

---

## ✨ That's It!

No complex setup needed for development. Just:

1. Request password reset
2. Watch your console
3. Copy the OTP
4. Done! 🎉

**Ready to test?** → `http://localhost:8000/login/resetPassword`
