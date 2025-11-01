<header>
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'User'); ?></div>
    <a class="btn" href="/login/logout">Logout</a>
</header>