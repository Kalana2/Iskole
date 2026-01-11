#!/bin/bash

# Email Configuration Test Script
echo "================================================"
echo "  ISKOLE - Email Configuration Test"
echo "================================================"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if PHPMailer is installed
echo -e "${YELLOW}Checking PHPMailer installation...${NC}"
if [ -d "vendor/phpmailer/phpmailer" ]; then
    echo -e "${GREEN}✓${NC} PHPMailer is installed"
else
    echo -e "${RED}✗${NC} PHPMailer not found. Run: composer require phpmailer/phpmailer"
    exit 1
fi

# Check if email config exists
echo -e "${YELLOW}Checking email configuration...${NC}"
if [ -f "app/Config/email.php" ]; then
    echo -e "${GREEN}✓${NC} Email config file exists"
else
    echo -e "${RED}✗${NC} Email config not found at app/Config/email.php"
    exit 1
fi

# Check if EmailService exists
echo -e "${YELLOW}Checking EmailService class...${NC}"
if [ -f "app/Core/EmailService.php" ]; then
    echo -e "${GREEN}✓${NC} EmailService class exists"
else
    echo -e "${RED}✗${NC} EmailService not found"
    exit 1
fi

# Check if storage/logs directory exists
echo -e "${YELLOW}Checking logs directory...${NC}"
if [ -d "storage/logs" ]; then
    echo -e "${GREEN}✓${NC} Logs directory exists"
else
    echo -e "${YELLOW}⚠${NC} Creating logs directory..."
    mkdir -p storage/logs
    chmod -R 775 storage
    echo -e "${GREEN}✓${NC} Logs directory created"
fi

# Check PHP syntax
echo -e "${YELLOW}Checking PHP syntax...${NC}"
php -l app/Core/EmailService.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} EmailService.php syntax OK"
else
    echo -e "${RED}✗${NC} EmailService.php has syntax errors"
    exit 1
fi

php -l app/Controllers/LoginController.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} LoginController.php syntax OK"
else
    echo -e "${RED}✗${NC} LoginController.php has syntax errors"
    exit 1
fi

echo ""
echo -e "${GREEN}================================================"
echo "  ✓ All Checks Passed!"
echo "================================================${NC}"
echo ""
echo "Next steps:"
echo "1. Configure your email settings in app/Config/email.php"
echo "2. For Gmail: Generate an App Password at https://myaccount.google.com/apppasswords"
echo "3. Update SMTP credentials in the config file"
echo "4. Test OTP: http://localhost/login/resetPassword"
echo "5. Check console logs: tail -f storage/logs/otp.log"
echo ""
echo "In DEVELOPMENT mode:"
echo "  - OTP will be logged to console/terminal"
echo "  - OTP will be saved to storage/logs/otp.log"
echo "  - Email will NOT be sent (unless SEND_EMAILS_IN_DEV=true)"
echo ""
echo "Read EMAIL-CONFIGURATION-GUIDE.md for detailed setup instructions"
echo ""
