<?php
/**
 * Email Template Preview and Testing Tool
 * This page allows you to preview and test email templates
 */

// Get template type from query parameter
$templateType = $_GET['template'] ?? 'otp';
$action = $_GET['action'] ?? 'preview';

// Sample data for preview
$sampleData = [
    'OTP_CODE' => '123456',
    'EXPIRY_MINUTES' => '10',
    'USER_NAME' => 'John Doe',
    'CURRENT_YEAR' => date('Y')
];

// Override with custom values if provided
if (isset($_GET['otp']))
    $sampleData['OTP_CODE'] = htmlspecialchars($_GET['otp']);
if (isset($_GET['expiry']))
    $sampleData['EXPIRY_MINUTES'] = htmlspecialchars($_GET['expiry']);
if (isset($_GET['name']))
    $sampleData['USER_NAME'] = htmlspecialchars($_GET['name']);

// Load and process template
function loadTemplate($filename, $data)
{
    $templatePath = __DIR__ . '/assets/email-templates/' . $filename;

    if (!file_exists($templatePath)) {
        return "<h1>❌ Template not found: {$filename}</h1>";
    }

    $template = file_get_contents($templatePath);

    // Replace placeholders
    foreach ($data as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    return $template;
}

// Handle different actions
if ($action === 'preview') {
    // Show the template preview
    if ($templateType === 'otp') {
        echo loadTemplate('otp-template.html', $sampleData);
    }
} elseif ($action === 'text') {
    // Show plain text version
    header('Content-Type: text/plain; charset=utf-8');
    if ($templateType === 'otp') {
        echo loadTemplate('otp-template.txt', $sampleData);
    }
} elseif ($action === 'test') {
    // Send a test email
    require_once __DIR__ . '/../app/Core/EmailService.php';

    $testEmail = $_POST['email'] ?? '';
    $userName = $_POST['name'] ?? 'Test User';

    if (!$testEmail) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit;
    }

    try {
        $emailService = new EmailService();
        $result = $emailService->sendOTP($testEmail, $sampleData['OTP_CODE'], $sampleData['EXPIRY_MINUTES'], $userName);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Test email sent successfully!' : 'Failed to send test email. Check logs.'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    // Show template selector and testing interface
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Template Preview - ISKOLE</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                padding: 20px;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                border-radius: 12px;
                padding: 30px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }

            h1 {
                color: #333;
                margin-bottom: 10px;
            }

            .subtitle {
                color: #666;
                margin-bottom: 30px;
                font-size: 14px;
            }

            .controls {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 30px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
                color: #333;
                font-size: 14px;
            }

            input,
            select {
                width: 100%;
                padding: 10px 15px;
                border: 2px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                transition: border-color 0.3s;
            }

            input:focus,
            select:focus {
                outline: none;
                border-color: #667eea;
            }

            .button-group {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }

            .btn-secondary {
                background: #6c757d;
                color: white;
            }

            .btn-secondary:hover {
                background: #5a6268;
            }

            .btn-success {
                background: #28a745;
                color: white;
            }

            .btn-success:hover {
                background: #218838;
            }

            .preview-frame {
                width: 100%;
                min-height: 600px;
                border: 2px solid #ddd;
                border-radius: 8px;
                background: white;
            }

            .info-box {
                background: #e7f3ff;
                border-left: 4px solid #667eea;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
            }

            .info-box p {
                margin: 5px 0;
                font-size: 14px;
                color: #333;
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
            }

            #test-result {
                margin-top: 15px;
                padding: 12px;
                border-radius: 6px;
                display: none;
            }

            #test-result.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                display: block;
            }

            #test-result.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                display: block;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>📧 Email Template Preview & Testing</h1>
            <p class="subtitle">Preview and test email templates for ISKOLE</p>

            <div class="info-box">
                <p><strong>💡 Quick Guide:</strong></p>
                <p>• Customize the OTP code, expiry time, and user name below</p>
                <p>• Click "Preview HTML" to see the email template</p>
                <p>• Click "View Plain Text" to see the text version</p>
                <p>• Use "Send Test Email" to send a real test email</p>
            </div>

            <div class="controls">
                <h3 style="margin-bottom: 20px;">Template Settings</h3>

                <div class="grid">
                    <div class="form-group">
                        <label for="otp">OTP Code:</label>
                        <input type="text" id="otp" value="123456" maxlength="6" pattern="[0-9]{6}">
                    </div>

                    <div class="form-group">
                        <label for="expiry">Expiry (minutes):</label>
                        <input type="number" id="expiry" value="10" min="1" max="60">
                    </div>

                    <div class="form-group">
                        <label for="name">User Name:</label>
                        <input type="text" id="name" value="John Doe">
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <h4 style="margin-bottom: 10px;">Actions:</h4>
                    <div class="button-group">
                        <button class="btn btn-primary" onclick="previewHTML()">🖼️ Preview HTML</button>
                        <button class="btn btn-secondary" onclick="viewText()">📄 View Plain Text</button>
                        <button class="btn btn-success" onclick="showTestForm()">📨 Send Test Email</button>
                    </div>
                </div>

                <div id="test-email-form"
                    style="display: none; margin-top: 20px; padding-top: 20px; border-top: 2px solid #ddd;">
                    <h4 style="margin-bottom: 15px;">Send Test Email</h4>
                    <div class="form-group">
                        <label for="test-email">Email Address:</label>
                        <input type="email" id="test-email" placeholder="your-email@example.com">
                    </div>
                    <button class="btn btn-success" onclick="sendTestEmail()">Send Now</button>
                    <div id="test-result"></div>
                </div>
            </div>

            <iframe id="preview-frame" class="preview-frame"></iframe>
        </div>

        <script>
            function getParams() {
                return {
                    otp: document.getElementById('otp').value,
                    expiry: document.getElementById('expiry').value,
                    name: document.getElementById('name').value
                };
            }

            function previewHTML() {
                const params = getParams();
                const url = `?action=preview&template=otp&otp=${params.otp}&expiry=${params.expiry}&name=${encodeURIComponent(params.name)}`;
                document.getElementById('preview-frame').src = url;
            }

            function viewText() {
                const params = getParams();
                const url = `?action=text&template=otp&otp=${params.otp}&expiry=${params.expiry}&name=${encodeURIComponent(params.name)}`;
                window.open(url, '_blank');
            }

            function showTestForm() {
                const form = document.getElementById('test-email-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

            function sendTestEmail() {
                const email = document.getElementById('test-email').value;
                const resultDiv = document.getElementById('test-result');

                if (!email) {
                    resultDiv.className = 'error';
                    resultDiv.textContent = '❌ Please enter an email address';
                    return;
                }

                resultDiv.className = '';
                resultDiv.textContent = '⏳ Sending test email...';
                resultDiv.style.display = 'block';

                const params = getParams();
                const formData = new FormData();
                formData.append('email', email);
                formData.append('name', params.name);

                fetch(`?action=test&otp=${params.otp}&expiry=${params.expiry}`, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        resultDiv.className = data.success ? 'success' : 'error';
                        resultDiv.textContent = (data.success ? '✅ ' : '❌ ') + data.message;
                    })
                    .catch(error => {
                        resultDiv.className = 'error';
                        resultDiv.textContent = '❌ Error: ' + error.message;
                    });
            }

            // Auto-preview on load
            window.onload = function () {
                previewHTML();
            };
        </script>
    </body>

    </html>
    <?php
}
?>