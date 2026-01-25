#!/bin/bash

# Reset Password Feature - Quick Test Script
# This script tests the reset password endpoints

echo "======================================"
echo "Reset Password Feature - Test Script"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Base URL - adjust if needed
BASE_URL="http://localhost"

echo -e "${YELLOW}Test 1: Checking Login Page${NC}"
curl -s -o /dev/null -w "Status: %{http_code}\n" "$BASE_URL/login"
echo ""

echo -e "${YELLOW}Test 2: Checking Reset Password Page (GET)${NC}"
curl -s -o /dev/null -w "Status: %{http_code}\n" "$BASE_URL/login/resetPassword"
echo ""

echo -e "${YELLOW}Test 3: Checking Set New Password Page (GET with token)${NC}"
curl -s -o /dev/null -w "Status: %{http_code}\n" "$BASE_URL/login/setNewPassword?token=test123"
echo ""

echo -e "${YELLOW}Test 4: Testing Reset Password POST (with email)${NC}"
curl -s -X POST "$BASE_URL/login/resetPassword" \
  -d "email=test@example.com" \
  -w "\nStatus: %{http_code}\n" \
  | grep -q "success" && echo -e "${GREEN}✓ Success message found${NC}" || echo -e "${RED}✗ Success message not found${NC}"
echo ""

echo -e "${YELLOW}Test 5: Testing Reset Password POST (empty email - should error)${NC}"
curl -s -X POST "$BASE_URL/login/resetPassword" \
  -d "email=" \
  -w "\nStatus: %{http_code}\n" \
  | grep -q "error" && echo -e "${GREEN}✓ Error message found${NC}" || echo -e "${RED}✗ Error message not found${NC}"
echo ""

echo -e "${YELLOW}Test 6: Testing Set New Password POST${NC}"
curl -s -X POST "$BASE_URL/login/setNewPassword" \
  -d "token=test123&password=newpassword123&confirm_password=newpassword123" \
  -w "\nStatus: %{http_code}\n" \
  | grep -q "success" && echo -e "${GREEN}✓ Success message found${NC}" || echo -e "${RED}✗ Success message not found${NC}"
echo ""

echo -e "${YELLOW}Test 7: Testing Password Mismatch${NC}"
curl -s -X POST "$BASE_URL/login/setNewPassword" \
  -d "token=test123&password=newpassword123&confirm_password=different123" \
  -w "\nStatus: %{http_code}\n" \
  | grep -q "error" && echo -e "${GREEN}✓ Error message found${NC}" || echo -e "${RED}✗ Error message not found${NC}"
echo ""

echo "======================================"
echo -e "${GREEN}Tests Complete!${NC}"
echo "======================================"
echo ""
echo "Manual Testing URLs:"
echo "  - Login: $BASE_URL/login"
echo "  - Reset Password: $BASE_URL/login/resetPassword"
echo "  - Set New Password: $BASE_URL/login/setNewPassword?token=test123"
echo ""
