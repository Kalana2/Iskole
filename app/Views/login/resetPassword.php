<div class="login-container">
    <div class="logo">
        <img src="/assets/logo.png" alt="Iskole Logo" height="100" width="100">
    </div>
    <h1>Reset Password</h1>
    <p class="subtitle" id="subtitle">
        <?php echo !empty($otpSent) ? 'Enter the OTP sent to your email' : 'Enter your email to receive OTP'; ?>
    </p>

    <div class="login-form">
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="/login/resetPassword" method="post" id="resetForm">
            <?php if (empty($otpSent)): ?>
                <!-- Step 1: Enter Email -->
                <input type="email" placeholder="Email Address" name="email" id="emailInput"
                    value="<?php echo isset($old['email']) ? htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    required>
                <button type="submit" name="action" value="send_otp" id="submitBtn">Send OTP</button>
            <?php else: ?>
                <!-- Step 2: Enter OTP -->
                <input type="hidden" name="email"
                    value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="text" placeholder="Enter 6-digit OTP" name="otp" id="otpInput" maxlength="6" pattern="[0-9]{6}"
                    required autocomplete="off">
                <div style="margin-top: 10px; text-align: right;">
                    <button type="button" id="resendOtp"
                        style="background: none; border: none; color: rgba(255, 255, 255, 0.8); font-size: 13px; cursor: pointer; text-decoration: underline;">
                        Resend OTP
                    </button>
                </div>
                <button type="submit" name="action" value="verify_otp" id="submitBtn">Verify OTP</button>
            <?php endif; ?>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/login"
                style="color: rgba(255, 255, 255, 0.9); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                ← Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    <?php if (!empty($otpSent)): ?>
        // Resend OTP functionality
        document.getElementById('resendOtp').addEventListener('click', function () {
            const form = document.getElementById('resetForm');
            const formData = new FormData(form);
            formData.set('action', 'resend_otp');

            fetch('/login/resetPassword', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OTP has been resent to your email');
                    } else {
                        alert(data.message || 'Failed to resend OTP');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to resend OTP');
                });
        });

        // Auto-focus OTP input
        document.getElementById('otpInput').focus();
    <?php endif; ?>
</script>