<div class="mb-4">
    <h2><i class="fa-solid fa-chart-line"></i> Sales Analytics</h2>
    <p class="text-muted">Monitor your business performance.</p>
</div>

<div class="grid-3 mb-4">
    <div class="stat-card">
        <i class="fa-solid fa-shopping-cart stat-icon"></i>
        <div class="stat-label">Total Completed Orders</div>
        <div class="stat-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-dollar-sign stat-icon"></i>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-receipt stat-icon"></i>
        <div class="stat-label">Average Order Value</div>
        <div class="stat-value">$<?= number_format($stats['avg_order_value'] ?? 0, 2) ?></div>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3 class="card-title">Top Selling Items</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th class="text-right">Total Quantity Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($stats['top_items'])): ?>
                    <tr><td colspan="2" class="text-muted text-center">No sales data yet.</td></tr>
                <?php else: foreach($stats['top_items'] as $item): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                        <td class="text-right"><span class="badge badge-accepted"><?= $item['total_sold'] ?></span></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
