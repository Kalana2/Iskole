<div class="login-container">
    <div class="logo">
        <img src="/assets/logo.png" alt="Iskole Logo" height="100" width="100">
    </div>
    <h1>Set New Password</h1>
    <p class="subtitle">Enter your new password below</p>

    <div class="login-form">
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="/login/setNewPassword" method="post">
            <input type="hidden" name="token"
                value="<?php echo isset($token) ? htmlspecialchars($token, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <input type="password" placeholder="New Password" name="password" required minlength="8">
            <input type="password" placeholder="Confirm Password" name="confirm_password" required minlength="8">
            <button type="submit">Reset Password</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/login"
                style="color: rgba(255, 255, 255, 0.9); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                ← Back to Login
            </a>
        </div>
    </div>
</div>