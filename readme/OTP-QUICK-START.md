# 🚀 OTP Password Reset - Quick Start Guide

## 1️⃣ Access the Page
```
http://localhost/login/resetPassword
```

## 2️⃣ Monitor Logs (in another terminal)
```bash
tail -f /var/log/apache2/error.log | grep 'Reset Password OTP'
```

## 3️⃣ Test Flow

### Step A: Request OTP
- Enter email: `teacher@iskole.com` (or any valid email from your DB)
- Click: **Send OTP**
- See: ✓ Success message

### Step B: Get OTP from Logs
Look for: `Reset Password OTP for teacher@iskole.com: 123456`

### Step C: Verify OTP
- Enter OTP: `123456`
- Click: **Verify OTP**
- Redirected to password page

### Step D: Set New Password
- Enter password: `newpassword123` (min 8 chars)
- Confirm: `newpassword123`
- Click: **Reset Password**
- See: ✓ Success message

### Step E: Login
- Use email and NEW password
- Should login successfully! ✓

## 📊 Quick Test Scenarios

| Test | Input | Expected Result |
|------|-------|----------------|
| Invalid email | `user@` | ❌ "Invalid email" |
| Wrong OTP | `999999` | ❌ "Invalid OTP" |
| Short password | `pass123` | ❌ "Too short" |
| Mismatched password | Different | ❌ "Don't match" |
| Valid flow | Correct | ✅ Success! |

## 🔧 Run Scripts

### Automated Tests
```bash
./scripts/test_otp_reset.sh
```

### Interactive Demo
```bash
./scripts/demo_otp_reset.sh
```

## 📚 Full Documentation
- `OTP-RESET-PASSWORD-GUIDE.md` - Complete guide
- `OTP-VISUAL-FLOW.md` - Flow diagrams
- `OTP-CHECKLIST.md` - Full checklist
- `OTP-FINAL-SUMMARY.md` - Implementation summary

## ⚡ Quick Tips
- OTP expires in **10 minutes**
- Reset token expires in **30 minutes**
- Password minimum **8 characters**
- Use **Resend OTP** if expired
- Check logs for OTP during development

## 🎯 Status
✅ **Core**: Complete  
✅ **Tests**: 18/18 Passed  
⏳ **Email**: Not configured (uses logs)  
🟢 **Ready**: For testing!

---
**Need help?** See `OTP-RESET-PASSWORD-GUIDE.md`
