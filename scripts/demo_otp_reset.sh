#!/bin/bash

# Interactive Demo Script for OTP Password Reset
# This script guides you through testing the complete flow

echo "╔════════════════════════════════════════════════════════════╗"
echo "║         ISKOLE - OTP Password Reset Demo                  ║"
echo "║         Interactive Testing Guide                          ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Function to print step
print_step() {
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${CYAN}STEP $1: $2${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo ""
}

# Function to wait for user
wait_user() {
    echo ""
    echo -e "${YELLOW}Press ENTER to continue...${NC}"
    read
    clear
}

clear

# Step 1: Pre-requisites
print_step "1" "Pre-requisites Check"
echo "Let's verify your setup first..."
echo ""

# Check if web server is running
if pgrep -x "apache2" > /dev/null || pgrep -x "httpd" > /dev/null; then
    echo -e "${GREEN}✓${NC} Web server is running"
else
    echo -e "${RED}✗${NC} Web server is not running"
    echo "  Please start Apache: sudo service apache2 start"
fi

# Check if database is accessible
if mysql -u root -e "SELECT 1" &> /dev/null; then
    echo -e "${GREEN}✓${NC} Database is accessible"
else
    echo -e "${YELLOW}!${NC} Database check skipped (may need password)"
fi

# Check if files exist
if [ -f "app/Controllers/LoginController.php" ]; then
    echo -e "${GREEN}✓${NC} LoginController exists"
else
    echo -e "${RED}✗${NC} LoginController not found"
fi

if [ -f "app/Views/login/resetPassword.php" ]; then
    echo -e "${GREEN}✓${NC} Reset password view exists"
else
    echo -e "${RED}✗${NC} Reset password view not found"
fi

echo ""
echo -e "${CYAN}Make sure you have:${NC}"
echo "  • A test user account in the database"
echo "  • Access to server logs (tail -f /var/log/apache2/error.log)"
echo "  • Web browser open"

wait_user

# Step 2: Access the reset page
print_step "2" "Access Reset Password Page"
echo "Open your web browser and navigate to:"
echo ""
echo -e "${GREEN}  http://localhost/login/resetPassword${NC}"
echo ""
echo "OR if using a custom domain:"
echo -e "${GREEN}  http://iskole.local/login/resetPassword${NC}"
echo ""
echo "You should see:"
echo "  • Beautiful glassmorphism login box on the right side"
echo "  • Title: 'Reset Password'"
echo "  • Subtitle: 'Enter your email to receive OTP'"
echo "  • Email input field"
echo "  • 'Send OTP' button"
echo "  • 'Back to Login' link"

wait_user

# Step 3: Open log monitoring
print_step "3" "Monitor Server Logs for OTP"
echo "Open a NEW TERMINAL window and run:"
echo ""
echo -e "${GREEN}  tail -f /var/log/apache2/error.log | grep 'Reset Password OTP'${NC}"
echo ""
echo "OR for PHP error log:"
echo -e "${GREEN}  tail -f /var/log/php_errors.log | grep 'Reset Password OTP'${NC}"
echo ""
echo "Keep this terminal visible - you'll see the OTP here!"
echo ""
echo -e "${YELLOW}Note:${NC} The OTP will appear in this format:"
echo "  Reset Password OTP for user@example.com: 123456"

wait_user

# Step 4: Request OTP
print_step "4" "Request OTP"
echo "In your web browser:"
echo ""
echo "1. Enter a valid email from your database"
echo "   Example: teacher@iskole.com"
echo ""
echo "2. Click the ${GREEN}'Send OTP'${NC} button"
echo ""
echo "3. Watch for the success message:"
echo "   ${GREEN}✓ OTP has been sent to your email address.${NC}"
echo ""
echo "4. The form should now show:"
echo "   • OTP input field (6 digits)"
echo "   • 'Resend OTP' button"
echo "   • 'Verify OTP' button"

wait_user

# Step 5: Get OTP from logs
print_step "5" "Retrieve OTP from Logs"
echo "Switch to your log monitoring terminal window."
echo ""
echo "Look for a line like:"
echo -e "${CYAN}  [Tue Dec 31 12:34:56 2024] Reset Password OTP for user@example.com: ${GREEN}123456${NC}"
echo ""
echo "Copy the 6-digit OTP code."
echo ""
echo -e "${YELLOW}Note:${NC} OTP is valid for ${RED}10 minutes${NC} only!"

wait_user

# Step 6: Verify OTP
print_step "6" "Verify OTP"
echo "Back in your web browser:"
echo ""
echo "1. Enter the 6-digit OTP you copied from logs"
echo ""
echo "2. Click ${GREEN}'Verify OTP'${NC} button"
echo ""
echo "3. If OTP is correct, you'll be redirected to the"
echo "   'Set New Password' page"
echo ""
echo -e "${YELLOW}Testing scenarios:${NC}"
echo "  • Try entering wrong OTP → Should show error"
echo "  • Try resending OTP → Should get new code in logs"
echo "  • Wait 10+ minutes → Should show expiry error"

wait_user

# Step 7: Set new password
print_step "7" "Set New Password"
echo "You should now see the 'Set New Password' page:"
echo ""
echo "1. Enter your new password (minimum 8 characters)"
echo ""
echo "2. Confirm your new password"
echo ""
echo "3. Click ${GREEN}'Reset Password'${NC} button"
echo ""
echo -e "${YELLOW}Testing scenarios:${NC}"
echo "  • Try password < 8 chars → Should show error"
echo "  • Try mismatched passwords → Should show error"
echo "  • Use valid password → Should succeed"

wait_user

# Step 8: Login with new password
print_step "8" "Login with New Password"
echo "After successful password reset:"
echo ""
echo "1. You'll be redirected to the login page"
echo ""
echo "2. You should see a success message:"
echo "   ${GREEN}✓ Your password has been reset successfully!${NC}"
echo "   ${GREEN}  Please login with your new password.${NC}"
echo ""
echo "3. Enter your email and NEW password"
echo ""
echo "4. Click 'Sign In'"
echo ""
echo "5. You should be logged in successfully!"

wait_user

# Step 9: Verify old password doesn't work
print_step "9" "Security Check"
echo "Let's verify the old password no longer works:"
echo ""
echo "1. Logout from your account"
echo ""
echo "2. Try to login with the OLD password"
echo ""
echo "3. You should see:"
echo "   ${RED}✗ Invalid credentials.${NC}"
echo ""
echo "This confirms the password was properly updated!"

wait_user

# Summary
clear
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                  Testing Complete! ✓                       ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}Congratulations!${NC} You've tested the complete OTP password reset flow."
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Summary of Features Tested:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${GREEN}✓${NC} Email validation"
echo -e "${GREEN}✓${NC} OTP generation (6 digits)"
echo -e "${GREEN}✓${NC} OTP delivery (via logs)"
echo -e "${GREEN}✓${NC} OTP verification"
echo -e "${GREEN}✓${NC} OTP expiry (10 minutes)"
echo -e "${GREEN}✓${NC} Resend OTP functionality"
echo -e "${GREEN}✓${NC} Reset token generation"
echo -e "${GREEN}✓${NC} Password validation"
echo -e "${GREEN}✓${NC} Password confirmation"
echo -e "${GREEN}✓${NC} Password update in database"
echo -e "${GREEN}✓${NC} Session management"
echo -e "${GREEN}✓${NC} Success message display"
echo -e "${GREEN}✓${NC} Old password invalidation"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Next Steps for Production:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1. ${YELLOW}Configure Email Sending${NC}"
echo "   • Install PHPMailer: composer require phpmailer/phpmailer"
echo "   • Configure SMTP settings"
echo "   • Update LoginController to send actual emails"
echo ""
echo "2. ${YELLOW}Add Rate Limiting${NC}"
echo "   • Limit OTP requests per email (e.g., 3 per hour)"
echo "   • Limit OTP requests per IP"
echo "   • Add cooldown period"
echo ""
echo "3. ${YELLOW}Implement Audit Logging${NC}"
echo "   • Log all password reset attempts"
echo "   • Track successful/failed verifications"
echo "   • Monitor for suspicious activity"
echo ""
echo "4. ${YELLOW}Security Enhancements${NC}"
echo "   • Add CAPTCHA for OTP requests"
echo "   • Implement account lockout after failed attempts"
echo "   • Add IP-based throttling"
echo ""
echo "5. ${YELLOW}Email Templates${NC}"
echo "   • Create professional HTML email templates"
echo "   • Add company branding"
echo "   • Include security tips"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Documentation Available:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "  • OTP-RESET-PASSWORD-GUIDE.md       - Comprehensive guide"
echo "  • OTP-IMPLEMENTATION-SUMMARY.md     - Implementation details"
echo "  • OTP-RESET-COMPLETE.md             - Quick reference"
echo "  • scripts/test_otp_reset.sh         - Automated tests"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${CYAN}Thank you for testing!${NC} 🎉"
echo ""
