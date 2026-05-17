<?php

define('HOST', 'localhost');
define('DB',   'online_food_ordering_system');
define('USER', 'root');
define('PASS', '');

function getDB() {
    static $instance = null;

    // Reuse the same connection (singleton)
    if ($instance !== null) {
        return $instance;
    }

    try {
        $instance = new PDO(
            "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8mb4",
            USER,
            PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        return $instance;

    } catch (PDOException $e) {
        // Detect if this is an AJAX / JSON request
        $isAjax = (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        );

        if ($isAjax) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
            exit;
        }

        // For normal page requests — show a user-friendly HTML error
        http_response_code(500);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Database Error | FoodPathai</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
            <style>
                *{margin:0;padding:0;box-sizing:border-box;}
                body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#F4F5F9;}
                .box{background:white;border-radius:20px;padding:48px;max-width:480px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.10);}
                .icon{font-size:56px;margin-bottom:20px;}
                h1{font-size:22px;font-weight:700;color:#1A1A2E;margin-bottom:10px;}
                p{font-size:14px;color:#6B7280;line-height:1.7;margin-bottom:20px;}
                .code{background:#F4F5F9;border-radius:8px;padding:12px 16px;font-size:13px;font-family:monospace;color:#E8621A;text-align:left;margin-bottom:24px;word-break:break-all;}
                a{display:inline-block;padding:11px 28px;background:linear-gradient(135deg,#E8621A,#F5A623);color:white;border-radius:30px;font-weight:600;font-size:14px;text-decoration:none;}
            </style>
        </head>
        <body>
            <div class="box">
                <div class="icon">🍽️</div>
                <h1>Database Not Connected</h1>
                <p>FoodPathai could not connect to the MySQL database. Make sure XAMPP MySQL is running and the database exists.</p>
                <div class="code">
                    Database: <strong><?= DB ?></strong><br>
                    Host: <strong><?= HOST ?></strong><br>
                    Error: <?= htmlspecialchars($e->getMessage()) ?>
                </div>
                <p style="margin-bottom:16px;">Run the setup script to create the database and tables:</p>
                <a href="/Food-Delevary-Site/setup.php">▶ Run Setup</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

?>