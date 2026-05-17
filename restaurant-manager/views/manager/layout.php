<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Manager Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/manager.css">
    <script>
        const savedTheme = localStorage.getItem('manager_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fa-solid fa-utensils"></i> Manager</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="?route=manager/dashboard" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/dashboard') !== false ? 'active' : '' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                    <li><a href="?route=manager/profile" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/profile') !== false ? 'active' : '' ?>"><i class="fa-solid fa-store"></i> Restaurant Profile</a></li>
                    <li><a href="?route=manager/menu" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/menu') !== false ? 'active' : '' ?>"><i class="fa-solid fa-list-ul"></i> Menu Management</a></li>
                    <li><a href="?route=manager/discounts" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/discounts') !== false ? 'active' : '' ?>"><i class="fa-solid fa-tags"></i> Discounts</a></li>
                    <li><a href="?route=manager/orders" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/orders') !== false ? 'active' : '' ?>"><i class="fa-solid fa-receipt"></i> Order History</a></li>
                    <li><a href="?route=manager/reviews" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/reviews') !== false ? 'active' : '' ?>"><i class="fa-solid fa-star"></i> Reviews</a></li>
                    <li><a href="?route=manager/analytics" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/analytics') !== false ? 'active' : '' ?>"><i class="fa-solid fa-chart-line"></i> Analytics</a></li>
                    <li><a href="?route=manager/complaints" class="<?= strpos($_SERVER['QUERY_STRING'], 'manager/complaints') !== false ? 'active' : '' ?>"><i class="fa-solid fa-circle-exclamation"></i> Complaints</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="?route=manager/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-left">
                    <h3><?= htmlspecialchars($restaurant['name'] ?? 'Setup Your Restaurant') ?></h3>
                </div>
                <div class="topbar-right" style="display: flex; align-items: center; gap: 20px;">
                    <button id="themeToggle" class="btn btn-secondary" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Toggle Theme">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['name'] ?? 'Manager') ?>&background=3b82f6&color=fff" alt="Profile">
                        <span><?= htmlspecialchars($_SESSION['name'] ?? 'Manager') ?></span>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($_SESSION['flash_error']) ?>
                        <?php unset($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fa-solid fa-check-circle"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?>
                        <?php unset($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <?php require $contentView; ?>
            </div>
        </main>
    </div>

    <script src="assets/js/manager.js"></script>
</body>
</html>
