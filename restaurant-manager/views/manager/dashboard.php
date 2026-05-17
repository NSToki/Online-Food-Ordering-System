<div class="mb-4">
    <h2><i class="fa-solid fa-gauge"></i> Live Dashboard</h2>
    <p class="text-muted">Manage your active orders in real-time.</p>
</div>

<div class="grid-3 mb-4">
    <div class="stat-card">
        <i class="fa-solid fa-shopping-bag stat-icon"></i>
        <div class="stat-label">Total Orders</div>
        <div class="stat-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-coins stat-icon"></i>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-chart-pie stat-icon"></i>
        <div class="stat-label">Avg Order Value</div>
        <div class="stat-value">$<?= number_format($stats['avg_order_value'] ?? 0, 2) ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-bolt"></i> Active Orders</h3>
        <span class="badge badge-pending" id="connection-status">Connecting...</span>
    </div>
    <div class="kanban-board" id="active-orders-container"></div>
</div>
