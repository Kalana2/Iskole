<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISKOLE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login/login.css">
</head>

<body>
    <?php
    $error = isset($error) ? $error : null;
    $success = null;

    // Check for password reset success message
    if (isset($_SESSION['password_reset_success']) && $_SESSION['password_reset_success']) {
        $success = 'Your password has been reset successfully! Please login with your new password.';
        unset($_SESSION['password_reset_success']);
    }

    include __DIR__ . '/login.php';
    ?>
</body>

</html>