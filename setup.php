<?php
/**
 * FoodPathai — One-click Database Setup Script
 * Visit: http://localhost/Food-Delevary-Site/setup.php
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'online_food_ordering_system';

$steps = [];
$allOk = true;

// ── STEP 1: Connect to MySQL (no DB selected yet) ──────────────
try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $steps[] = ['ok', 'Connected to MySQL server'];
} catch (PDOException $e) {
    $steps[] = ['err', 'Cannot connect to MySQL: ' . $e->getMessage()];
    $allOk = false;
    goto render;
}

// ── STEP 2: Create database ────────────────────────────────────
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`
        CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");
    $steps[] = ['ok', "Database <strong>$dbName</strong> ready"];
} catch (PDOException $e) {
    $steps[] = ['err', 'Failed to create database: ' . $e->getMessage()];
    $allOk = false;
    goto render;
}

// ── STEP 3: Create tables ──────────────────────────────────────
$tables = [

    'users' => "CREATE TABLE IF NOT EXISTS `users` (
        `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name`          VARCHAR(120)  NOT NULL,
        `email`         VARCHAR(180)  NOT NULL UNIQUE,
        `password_hash` VARCHAR(255)  NOT NULL,
        `phone`         VARCHAR(20)   DEFAULT NULL,
        `role`          ENUM('admin','manager','customer','agent') NOT NULL DEFAULT 'customer',
        `profile_pic`   LONGBLOB      DEFAULT NULL,
        `is_active`     TINYINT(1)    NOT NULL DEFAULT 1,
        `created_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'restaurants' => "CREATE TABLE IF NOT EXISTS `restaurants` (
        `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `manager_id`  INT UNSIGNED NOT NULL,
        `name`        VARCHAR(180) NOT NULL,
        `cuisine_type`VARCHAR(100) DEFAULT NULL,
        `address`     VARCHAR(255) DEFAULT NULL,
        `city`        VARCHAR(100) DEFAULT NULL,
        `is_approved` TINYINT(1)   NOT NULL DEFAULT 0,
        `is_open`     TINYINT(1)   NOT NULL DEFAULT 1,
        `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'delivery_agents' => "CREATE TABLE IF NOT EXISTS `delivery_agents` (
        `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `user_id`     INT UNSIGNED NOT NULL,
        `vehicle_type`VARCHAR(60)  NOT NULL DEFAULT 'Motorcycle',
        `is_online`   TINYINT(1)   NOT NULL DEFAULT 0,
        `is_approved` TINYINT(1)   NOT NULL DEFAULT 0,
        `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'orders' => "CREATE TABLE IF NOT EXISTS `orders` (
        `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `customer_id`   INT UNSIGNED NOT NULL,
        `restaurant_id` INT UNSIGNED NOT NULL,
        `agent_id`      INT UNSIGNED DEFAULT NULL,
        `status`        ENUM('pending','accepted','preparing','ready','picked_up','on_the_way','delivered','cancelled') NOT NULL DEFAULT 'pending',
        `total_amount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `delivery_fee`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `created_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`customer_id`)   REFERENCES `users`(`id`)           ON DELETE CASCADE,
        FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`)     ON DELETE CASCADE,
        FOREIGN KEY (`agent_id`)      REFERENCES `delivery_agents`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'complaints' => "CREATE TABLE IF NOT EXISTS `complaints` (
        `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `submitter_id` INT UNSIGNED NOT NULL,
        `subject`      VARCHAR(255) NOT NULL,
        `description`  TEXT         NOT NULL,
        `status`       ENUM('open','resolved') NOT NULL DEFAULT 'open',
        `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`submitter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'platform_settings' => "CREATE TABLE IF NOT EXISTS `platform_settings` (
        `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `setting_key`   VARCHAR(100) NOT NULL UNIQUE,
        `setting_value` TEXT DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($tables as $name => $sql) {
    try {
        $pdo->exec($sql);
        $steps[] = ['ok', "Table <strong>$name</strong> created"];
    } catch (PDOException $e) {
        $steps[] = ['err', "Failed table $name: " . $e->getMessage()];
        $allOk = false;
    }
}

// ── STEP 4: Seed default settings ─────────────────────────────
$defaultSettings = [
    ['commission_rate_pct',  '10'],
    ['base_delivery_fee',    '2.50'],
    ['delivery_fee_per_km',  '0.50'],
    ['cuisine_categories',   json_encode(['Burger','Pizza','Sushi','Biryani','Salad','Dessert','Chinese','Indian','Thai','Fast Food'])],
];

foreach ($defaultSettings as [$key, $val]) {
    try {
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO `platform_settings` (`setting_key`, `setting_value`) VALUES (?, ?)"
        );
        $stmt->execute([$key, $val]);
    } catch (PDOException $e) {
        $steps[] = ['err', "Failed setting $key: " . $e->getMessage()];
        $allOk = false;
    }
}
$steps[] = ['ok', 'Default platform settings seeded'];

render:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup | FoodPathai</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#F4F5F9;padding:40px 20px;}
        .card{background:white;border-radius:24px;padding:48px;max-width:520px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,0.10);}
        .header{text-align:center;margin-bottom:36px;}
        .logo{width:70px;height:70px;background:linear-gradient(135deg,#E8621A,#F5A623);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:30px;color:white;margin:0 auto 18px;box-shadow:0 8px 24px rgba(232,98,26,0.35);}
        h1{font-size:24px;font-weight:800;color:#1A1A2E;}
        .subtitle{font-size:14px;color:#6B7280;margin-top:6px;}
        .steps{display:flex;flex-direction:column;gap:10px;margin-bottom:32px;}
        .step{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:10px;font-size:14px;}
        .step.ok {background:rgba(16,185,129,0.08);color:#065F46;}
        .step.err{background:rgba(239,68,68,0.08);color:#991B1B;}
        .step i{font-size:16px;flex-shrink:0;}
        .btn{display:block;width:100%;padding:14px;border-radius:12px;font-family:'Inter',sans-serif;font-size:15px;font-weight:700;text-align:center;text-decoration:none;cursor:pointer;border:none;transition:all 0.2s;}
        .btn-primary{background:linear-gradient(135deg,#E8621A,#F5A623);color:white;box-shadow:0 6px 20px rgba(232,98,26,0.30);}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(232,98,26,0.40);}
        .btn-danger{background:linear-gradient(135deg,#DC2626,#EF4444);color:white;}
        .final-msg{text-align:center;font-size:15px;font-weight:600;margin-bottom:20px;padding:14px;border-radius:12px;}
        .final-msg.ok {background:rgba(16,185,129,0.10);color:#065F46;}
        .final-msg.err{background:rgba(239,68,68,0.10);color:#991B1B;}
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div class="logo"><i class="fa-solid fa-motorcycle"></i></div>
        <h1>FoodPathai Setup</h1>
        <p class="subtitle">Database initialization for <strong>online_food_ordering_system</strong></p>
    </div>

    <div class="steps">
        <?php foreach ($steps as [$type, $msg]) : ?>
            <div class="step <?= $type ?>">
                <i class="fa-solid <?= $type === 'ok' ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                <span><?= $msg ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($allOk) : ?>
        <div class="final-msg ok">
            <i class="fa-solid fa-check-double"></i> Setup complete! Database is ready.
        </div>
        <a href="/Food-Delevary-Site/controlers/admin/AuthController.php?action=register_admin" class="btn btn-primary">
            <i class="fa-solid fa-user-plus"></i> Create Admin Account →
        </a>
    <?php else : ?>
        <div class="final-msg err">
            <i class="fa-solid fa-triangle-exclamation"></i> Setup failed. Fix errors above and retry.
        </div>
        <a href="/Food-Delevary-Site/setup.php" class="btn btn-danger">
            <i class="fa-solid fa-rotate-right"></i> Retry Setup
        </a>
    <?php endif; ?>
</div>
</body>
</html>
