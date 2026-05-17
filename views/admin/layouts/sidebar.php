<aside class="sidebar">

    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fa-solid fa-motorcycle"></i>
        </div>
        <div class="sidebar-brand">
            <span class="brand-name">FoodPathai</span>
            <span class="brand-sub">Admin Panel</span>
        </div>
    </div>

    <?php $uri = $_SERVER['REQUEST_URI']; ?>

    <nav class="sidebar-nav">

        <div class="nav-section">
            <span class="nav-section-label">Overview</span>
            <a href="/Food-Delevary-Site/controlers/admin/DashboardController.php"
               class="nav-item <?= strpos($uri, 'DashboardController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Restaurants</span>
            <a href="/Food-Delevary-Site/controlers/admin/ResturentController.php?action=list"
               class="nav-item <?= strpos($uri, 'action=list') !== false && strpos($uri, 'Resturent') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-store"></i>
                <span>All Restaurants</span>
            </a>
            <a href="/Food-Delevary-Site/controlers/admin/ResturentController.php?action=pending"
               class="nav-item <?= strpos($uri, 'action=pending') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-clock"></i>
                <span>Pending Approvals</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Users</span>
            <a href="/Food-Delevary-Site/controlers/admin/CustomerController.php"
               class="nav-item <?= strpos($uri, 'CustomerController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i>
                <span>Customers</span>
            </a>
            <a href="/Food-Delevary-Site/controlers/admin/AgentController.php?action=list"
               class="nav-item <?= strpos($uri, 'AgentController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-person-biking"></i>
                <span>Delivery Agents</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Activity</span>
            <a href="/Food-Delevary-Site/controlers/admin/OrderController.php"
               class="nav-item <?= strpos($uri, 'OrderController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>Orders</span>
            </a>
            <a href="/Food-Delevary-Site/controlers/admin/ComplainController.php"
               class="nav-item <?= strpos($uri, 'ComplainController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Complaints</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Platform</span>
            <a href="/Food-Delevary-Site/controlers/admin/AnalyticsController.php"
               class="nav-item <?= strpos($uri, 'AnalyticsController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="/Food-Delevary-Site/controlers/admin/SettingsController.php"
               class="nav-item <?= strpos($uri, 'SettingsController') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </a>
        </div>

        <div class="sidebar-footer-nav">
            <a href="/Food-Delevary-Site/controlers/admin/AuthController.php?action=logout" class="nav-item logout-item">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>

    </nav>

</aside>