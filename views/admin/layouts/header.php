<?php
// Helper to get correct relative paths based on depth
$base_url = '/Food-Delevary-Site/views/admin';
$pageTitle = $pageTitle ?? 'Platform Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?> | FoodPathai Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FoodPathai Admin Dashboard - Manage your food delivery platform">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Food-Delevary-Site/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <?php require_once __DIR__ . '/navbar.php'; ?>
        <div class="content-wrapper">