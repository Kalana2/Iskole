#!/bin/bash

# OTP Password Reset Test Script
# This script tests the OTP-based password reset functionality

echo "================================================"
echo "  ISKOLE - OTP Password Reset Test Suite"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
PASSED=0
FAILED=0

# Function to print test result
print_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✓ PASSED${NC}: $2"
        ((PASSED++))
    else
        echo -e "${RED}✗ FAILED${NC}: $2"
        ((FAILED++))
    fi
}

echo -e "${YELLOW}Testing File Existence...${NC}"
echo "----------------------------------------"

# Test 1: Check if controller method exists
if grep -q "public function resetPassword" app/Controllers/LoginController.php; then
    print_result 0 "LoginController::resetPassword() exists"
else
    print_result 1 "LoginController::resetPassword() missing"
fi

# Test 2: Check if generateOTP method exists
if grep -q "private function generateOTP" app/Controllers/LoginController.php; then
    print_result 0 "LoginController::generateOTP() exists"
else
    print_result 1 "LoginController::generateOTP() missing"
fi

# Test 3: Check if setNewPassword method exists
if grep -q "public function setNewPassword" app/Controllers/LoginController.php; then
    print_result 0 "LoginController::setNewPassword() exists"
else
    print_result 1 "LoginController::setNewPassword() missing"
fi

# Test 4: Check if UserModel updatePassword exists
if grep -q "public function updatePassword" app/Model/UserModel.php; then
    print_result 0 "UserModel::updatePassword() exists"
else
    print_result 1 "UserModel::updatePassword() missing"
fi

# Test 5: Check reset password view
if [ -f "app/Views/login/resetPassword.php" ]; then
    print_result 0 "resetPassword.php view exists"
else
    print_result 1 "resetPassword.php view missing"
fi

# Test 6: Check reset password index
if [ -f "app/Views/login/resetPasswordIndex.php" ]; then
    print_result 0 "resetPasswordIndex.php exists"
else
    print_result 1 "resetPasswordIndex.php missing"
fi

# Test 7: Check set new password view
if [ -f "app/Views/login/setNewPassword.php" ]; then
    print_result 0 "setNewPassword.php view exists"
else
    print_result 1 "setNewPassword.php view missing"
fi

# Test 8: Check set new password index
if [ -f "app/Views/login/setNewPasswordIndex.php" ]; then
    print_result 0 "setNewPasswordIndex.php exists"
else
    print_result 1 "setNewPasswordIndex.php missing"
fi

# Test 9: Check CSS success class
if grep -q "\.success" public/css/login/login.css; then
    print_result 0 "CSS .success class exists"
else
    print_result 1 "CSS .success class missing"
fi

# Test 10: Check forgot password link in login
if grep -q "Forgot Password" app/Views/login/login.php; then
    print_result 0 "Forgot Password link in login page"
else
    print_result 1 "Forgot Password link missing"
fi

echo ""
echo -e "${YELLOW}Testing OTP Implementation...${NC}"
echo "----------------------------------------"

# Test 11: Check OTP session handling
if grep -q "reset_otp" app/Controllers/LoginController.php; then
    print_result 0 "OTP session handling implemented"
else
    print_result 1 "OTP session handling missing"
fi

# Test 12: Check OTP expiry
if grep -q "otp_expiry" app/Controllers/LoginController.php; then
    print_result 0 "OTP expiry mechanism exists"
else
    print_result 1 "OTP expiry mechanism missing"
fi

# Test 13: Check resend OTP functionality
if grep -q "resend_otp" app/Controllers/LoginController.php; then
    print_result 0 "Resend OTP functionality exists"
else
    print_result 1 "Resend OTP functionality missing"
fi

# Test 14: Check OTP verification
if grep -q "verify_otp" app/Controllers/LoginController.php; then
    print_result 0 "OTP verification logic exists"
else
    print_result 1 "OTP verification logic missing"
fi

echo ""
echo -e "${YELLOW}Testing Security Features...${NC}"
echo "----------------------------------------"

# Test 15: Check password hashing
if grep -q "password_hash" app/Controllers/LoginController.php; then
    print_result 0 "Password hashing implemented"
else
    print_result 1 "Password hashing missing"
fi

# Test 16: Check password length validation
if grep -q "strlen(\$password) < 8" app/Controllers/LoginController.php; then
    print_result 0 "Password length validation exists"
else
    print_result 1 "Password length validation missing"
fi

# Test 17: Check password confirmation
if grep -q "confirm_password" app/Controllers/LoginController.php; then
    print_result 0 "Password confirmation check exists"
else
    print_result 1 "Password confirmation check missing"
fi

# Test 18: Check token validation
if grep -q "reset_token" app/Controllers/LoginController.php; then
    print_result 0 "Reset token validation exists"
else
    print_result 1 "Reset token validation missing"
fi

echo ""
echo "================================================"
echo "  Test Summary"
echo "================================================"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}All tests passed! ✓${NC}"
    echo ""
    echo "Next steps:"
    echo "1. Configure email sending (see OTP-RESET-PASSWORD-GUIDE.md)"
    echo "2. Test the flow at: http://localhost/login/resetPassword"
    echo "3. Check logs for OTP: tail -f /var/log/php_errors.log"
    exit 0
else
    echo -e "${RED}Some tests failed. Please review the errors above.${NC}"
    exit 1
fi
