<header>
    <div>Welcome,
        <span><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>
        <span><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'User'); ?></span>
    </div>
    <a class="btn" href="/login/logout">Logout</a>
</header>