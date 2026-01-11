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
        <form action="/login/authenticate" method="post">
            <input type="email" placeholder="Email Address" name="email"
                value="<?php echo isset($old['email']) ? htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Sign In</button>
        </form>
    </div>
</div>