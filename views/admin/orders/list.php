<?php
$pageTitle = 'Order Monitoring';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Order Monitoring</h1>
    <p>Track and filter all orders across the platform in real time</p>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2>All Orders</h2>
            <p>Filter by status to narrow down results</p>
        </div>
        <form method="GET" class="filter-bar" style="margin-bottom:0;">
            <label for="statusFilter"><i class="fa-solid fa-filter"></i> Status:</label>
            <select name="status" id="statusFilter" onchange="this.form.submit()">
                <?php
                $statuses = [
                    'all', 'pending', 'accepted', 'preparing',
                    'ready', 'picked_up', 'on_the_way',
                    'delivered', 'cancelled'
                ];
                foreach ($statuses as $s) {
                    $selected = ($statusFilter == $s) ? 'selected' : '';
                    $label = ucfirst(str_replace('_', ' ', $s));
                    echo "<option value='" . htmlspecialchars($s) . "' $selected>" . htmlspecialchars($label) . "</option>";
                }
                ?>
            </select>
        </form>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Agent</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)) : ?>
                    <?php foreach ($orders as $o) : ?>
                        <?php
                            $statusMap = [
                                'delivered'  => 'badge-success',
                                'pending'    => 'badge-warning',
                                'cancelled'  => 'badge-danger',
                                'on_the_way' => 'badge-info',
                                'picked_up'  => 'badge-info',
                                'preparing'  => 'badge-warning',
                                'accepted'   => 'badge-info',
                                'ready'      => 'badge-success',
                            ];
                            $statusClass = $statusMap[$o['status']] ?? 'badge-muted';
                        ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($o['id']) ?></strong></td>
                            <td><?= htmlspecialchars($o['customer_name']) ?></td>
                            <td><?= htmlspecialchars($o['restaurant_name']) ?></td>
                            <td>
                                <?php if (!empty($o['agent_name'])) : ?>
                                    <span>
                                        <i class="fa-solid fa-person-biking" style="color:var(--primary);margin-right:5px;"></i>
                                        <?= htmlspecialchars($o['agent_name']) ?>
                                    </span>
                                <?php else : ?>
                                    <span style="color:var(--text-light);font-size:13px;">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td><strong>$<?= htmlspecialchars($o['total_amount']) ?></strong></td>
                            <td>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $o['status']))) ?>
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;">
                                <?= htmlspecialchars(date('M d, Y H:i', strtotime($o['created_at']))) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <p>No orders found for this filter</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>