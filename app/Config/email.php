<?php
/**
 * Email Configuration
 * 
 * Configure your SMTP settings here
 * For Gmail: Use App Password (not regular password)
 * For other providers: Check their SMTP settings
 */

return [
    // SMTP Configuration
    'smtp_host' => 'smtp.gmail.com',  // Change for your email provider
    'smtp_port' => 587,                // 587 for TLS, 465 for SSL
    'smtp_secure' => 'tls',            // 'tls' or 'ssl'
    'smtp_auth' => true,

    // Email Credentials
    'smtp_username' => 'your-email@gmail.com',  // Your email address
    'smtp_password' => 'your-app-password',     // Your app password (not regular password!)

    // From Address
    'from_email' => 'noreply@iskole.com',
    'from_name' => 'ISKOLE - School Management System',

    // Email Settings
    'charset' => 'UTF-8',
    'debug' => 0,  // 0 = off, 1 = client, 2 = server, 3 = connection

    // Development Mode
    'development_mode' => true,  // Set to false in production
    'log_emails' => true,        // Log emails to console/file in dev mode
];
