<?php
$pageTitle = 'Analytics';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Platform Analytics</h1>
    <p>Revenue metrics, monthly trends, and top-performing restaurants</p>
</div>

<!-- REVENUE METRIC CARDS -->
<div class="stats-grid" style="margin-bottom:32px;">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-body">
            <div class="stat-value">$<?= number_format($totalRevenue ?? 0, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-percent"></i></div>
        <div class="stat-body">
            <div class="stat-value">$<?= number_format($totalCommission ?? 0, 2) ?></div>
            <div class="stat-label">Platform Commission</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-truck-fast"></i></div>
        <div class="stat-body">
            <div class="stat-value">$<?= number_format($deliveryFees ?? 0, 2) ?></div>
            <div class="stat-label">Delivery Fees</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    <!-- MONTHLY REVENUE TABLE -->
    <div class="card">
        <div class="card-header">
            <div>
                <h2><i class="fa-solid fa-chart-line" style="color:var(--primary);margin-right:8px;"></i>Monthly Revenue</h2>
                <p>Revenue breakdown by month</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($monthlyRevenue)) : ?>
                        <?php foreach ($monthlyRevenue as $row) : ?>
                            <tr>
                                <td style="font-weight:600;">
                                    <?= htmlspecialchars(date('F Y', strtotime($row['month'] . '-01'))) ?>
                                </td>
                                <td><?= htmlspecialchars($row['total_orders']) ?></td>
                                <td><strong style="color:var(--primary);">$<?= number_format($row['revenue'], 2) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fa-solid fa-chart-line"></i>
                                    <p>No monthly data available</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOP RESTAURANTS TABLE -->
    <div class="card">
        <div class="card-header">
            <div>
                <h2><i class="fa-solid fa-trophy" style="color:var(--warning);margin-right:8px;"></i>Top Restaurants</h2>
                <p>Highest revenue-generating restaurants</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Restaurant</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topRestaurants)) : ?>
                        <?php foreach ($topRestaurants as $i => $r) : ?>
                            <?php
                                $rankColors = ['#F59E0B', '#9CA3AF', '#CD7F32'];
                                $rankColor  = $rankColors[$i] ?? 'var(--text-muted)';
                            ?>
                            <tr>
                                <td>
                                    <span style="font-size:16px;color:<?= $rankColor ?>;">
                                        <?php if ($i < 3) : ?>
                                            <i class="fa-solid fa-medal"></i>
                                        <?php else : ?>
                                            <strong style="color:var(--text-muted);">#<?= $i + 1 ?></strong>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td style="font-weight:600;"><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['order_count']) ?></td>
                                <td><strong style="color:var(--primary);">$<?= number_format($r['revenue'], 2) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fa-solid fa-trophy"></i>
                                    <p>No restaurant data available</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
