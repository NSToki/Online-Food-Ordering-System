<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Platform Overview</h1>
    <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong> — here's what's happening today.</p>
</div>

<!-- METRIC CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-store"></i></div>
        <div class="stat-body">
            <div class="stat-value" id="stat-activeRestaurants"><?= htmlspecialchars($activeRestaurants) ?></div>
            <div class="stat-label">Active Restaurants</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-bag-shopping"></i></div>
        <div class="stat-body">
            <div class="stat-value" id="stat-ordersToday"><?= htmlspecialchars($ordersToday) ?></div>
            <div class="stat-label">Orders Today</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-users"></i></div>
        <div class="stat-body">
            <div class="stat-value" id="stat-totalUsers"><?= htmlspecialchars($totalUsers) ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa-solid fa-person-biking"></i></div>
        <div class="stat-body">
            <div class="stat-value" id="stat-activeAgents"><?= htmlspecialchars($activeAgents) ?></div>
            <div class="stat-label">Active Agents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-body">
            <div class="stat-value" id="stat-totalRevenue">$<?= number_format($totalRevenue, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
</div>

<!-- RECENT ORDERS TABLE -->
<div class="card">
    <div class="card-header">
        <div>
            <h2>Recent Orders</h2>
            <p>Latest activity across the platform</p>
        </div>
        <a href="/Food-Delevary-Site/controlers/admin/OrderController.php" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-arrow-right"></i> View All
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentOrders)) : ?>
                    <?php foreach ($recentOrders as $order) : ?>
                        <?php
                            $statusMap = [
                                'delivered'  => 'badge-success',
                                'pending'    => 'badge-warning',
                                'cancelled'  => 'badge-danger',
                                'on_the_way' => 'badge-info',
                                'preparing'  => 'badge-warning',
                                'accepted'   => 'badge-info',
                            ];
                            $statusClass = $statusMap[$order['status']] ?? 'badge-muted';
                        ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($order['id']) ?></strong></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                            <td><strong>$<?= htmlspecialchars($order['total_amount']) ?></strong></td>
                            <td>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $order['status']))) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(date('M d, Y H:i', strtotime($order['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <p>No recent orders found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>