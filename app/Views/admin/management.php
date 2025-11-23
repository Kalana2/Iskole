<section class="management">
    <!-- deguggin -->
    <?php
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }
    
    // if (!empty($_SESSION['mgmt_msg'])) {
    //     echo '<div class="alert alert-info" role="status">' . htmlspecialchars($_SESSION['mgmt_msg']) . '</div>';
    //     unset($_SESSION['mgmt_msg']);
    // }
    
    // if (!empty($_GET['debug']) && $_GET['debug'] === '1') {
    //     echo '<pre class="debug-session">' . htmlspecialchars(print_r($_SESSION, true)) . '</pre>';
    // }
    ?>

    <?php include_once __DIR__ . '/../templates/userDirectory.php'; ?>
    <?php include_once __DIR__ . '/../templates/addNewUser.php'; ?>
</section>