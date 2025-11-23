<header class="header">
    <link rel="stylesheet" type="text/css" href="/css/header/header.css">
    <link rel="stylesheet" href="/css/navigation/navigation.css">

    <div class="header-container">
        <div class="header-left">
            <div class="logo">
                <img src="/assets/logo.png" alt="Iskole Logo">
                <span class="logo-text">Iskole</span>
            </div>
        </div>

        <div class="header-right">
            <div class="user-info">
                <div class="user-avatar">
                    <?php
                    $name = $_SESSION['name'] ?? 'User';
                    echo strtoupper(substr($name, 0, 1));
                    ?>
                </div>
                <div class="user-details">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>
                    <span
                        class="user-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com'); ?></span>
                </div>
            </div>

            <a class="logout-btn" href="/login/logout">
                <img src="/assets/logout.svg" alt="Logout">
                <span>Logout</span>
            </a>
        </div>
    </div>
</header>