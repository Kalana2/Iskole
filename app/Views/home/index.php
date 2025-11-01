<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/css/variables.css">
    <style>
        body {
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        main {
            padding: 24px;
        }

        .btn {
            padding: 10px 14px;
            background: #667eea;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../header/index.php'; ?>
    <main>
        <h1>Dashboard</h1>
        <p>You are logged in.</p>
    </main>
</body>

</html>