<section class="management">
    <?php if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } ?>
    <?php if (!empty($_SESSION['mgmt_msg'])): ?>
        <div class="alert alert-info" role="status"><?php echo htmlspecialchars($_SESSION['mgmt_msg']); ?></div>
        <?php unset($_SESSION['mgmt_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($_GET['debug']) && $_GET['debug'] === '1'): ?>
        <pre class="debug-session"><?php echo htmlspecialchars(print_r($_SESSION, true)); ?></pre>
    <?php endif; ?>

    <?php include_once __DIR__ . '/../templates/userDirectory.php'; ?>
    <?php include_once __DIR__ . '/../templates/addNewUser.php'; ?>
</section>