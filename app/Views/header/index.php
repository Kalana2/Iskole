<header class="header" role="banner">
    <link rel="stylesheet" type="text/css" href="/css/header/header.css">
    <link rel="stylesheet" type="text/css" href="/css/navigation/navigation.css">

    <div class="header-container">
        <div class="header-left">
            <a href="/" class="logo" aria-label="Iskole Home">
                <div class="logo-icon-wrapper">
                    <img src="/assets/logo.png" alt="Iskole Logo" loading="eager">
                </div>
                <span class="logo-text">Iskole</span>
            </a>
        </div>

        <nav class="header-right" role="navigation" aria-label="User navigation">
            <div class="user-info" tabindex="0" role="button" aria-label="User account">
                <div class="user-avatar" aria-hidden="true">
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

            <a class="logout-btn" href="/login/logout" aria-label="Logout from account">
                <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span class="logout-text">Logout</span>
            </a>
        </nav>
    </div>

    <script src="/js/header/header.js" defer></script>
</header>