<div class="login-container">
    <div class="logo">
        <img src="/assets/logo.png" alt="Iskole Logo" height="100" width="100">
    </div>
    <h1>Welcome Back</h1>
    <p class="subtitle">Login to your account</p>

    <div class="login-form">
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="/login/authenticate" method="post">
            <input type="email" placeholder="Email Address" name="email"
                value="<?php echo isset($old['email']) ? htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Sign In</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/login/resetPassword"
                style="color: rgba(255, 255, 255, 0.9); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;">
                Forgot Password?
            </a>
        </div>
    </div>
</div>