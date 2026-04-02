<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - ISKOLE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login/login.css">
</head>

<body>
    <?php
    $error = isset($error) ? $error : null;
    $success = isset($success) ? $success : null;
    $token = isset($token) ? $token : '';
    include __DIR__ . '/setNewPassword.php';
    ?>
</body>

</html>