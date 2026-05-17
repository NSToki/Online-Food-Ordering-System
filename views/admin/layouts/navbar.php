<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-page-title" id="topbarPageTitle"></div>
    </div>
    <div class="topbar-right">
        <!-- Real-time clock -->
        <div class="realtime-clock">
            <div class="clock-time" id="realtimeTime">--:--:<span class="seconds">--</span></div>
            <div class="clock-date" id="realtimeDate">Loading...</div>
        </div>
        <!-- Admin avatar -->
        <a href="/Food-Delevary-Site/controlers/admin/ProfileController.php" class="user-profile-link">
            <?php if (!empty($_SESSION['admin_profile_pic'])): ?>
                <img src="data:image/jpeg;base64,<?= htmlspecialchars($_SESSION['admin_profile_pic']) ?>"
                     alt="Admin Avatar" class="avatar-img">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name'] ?? 'Admin') ?>&background=E8621A&color=fff&bold=true"
                     alt="<?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?> Avatar"
                     class="avatar-img">
            <?php endif; ?>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <span class="user-role">Platform Admin</span>
            </div>
            <i class="fa-solid fa-chevron-down user-chevron"></i>
        </a>
    </div>
</header>