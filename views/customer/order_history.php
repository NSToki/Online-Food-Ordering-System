<?php
$pageTitle = 'Order History | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.order-card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 20px; border: 1px solid var(--border); overflow: hidden; }
.order-header { padding: 15px 20px; background: #f8fafc; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
.order-header-left { display: flex; gap: 20px; }
.order-meta-label { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
.order-meta-val { font-size: 14px; font-weight: 600; color: var(--text); }
.order-body { padding: 20px; display: flex; justify-content: space-between; align-items: center; }
@media (max-width: 600px) { .order-body { flex-direction: column; align-items: flex-start; gap: 15px; } }
.rest-name { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
.status-pill { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
.status-pending { background:#fff8e1;color:#b45309; }
.status-preparing { background:#ede7f6;color:#512da8; }
.status-delivered { background:#e8faf5;color:#05875d; }
.status-cancelled { background:#ffebee;color:#c62828; }
.status-accepted, .status-ready, .status-picked_up, .status-on_the_way { background:#e3f2fd;color:#1565c0; }
</style>

<h2 class="page-title"><i class="fa-solid fa-clock-rotate-left"></i> My Orders</h2>

<?php if (isset($_GET['cancelled'])): ?>
    <div class="alert alert-success"><i class="fa-solid fa-check"></i> Order successfully cancelled.</div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="card empty-state" style="max-width: 600px; margin: 0 auto;">
        <i class="fa-solid fa-receipt"></i>
        <p>You haven't placed any orders yet.</p>
        <a href="index.php?action=dashboard" class="btn"><i class="fa-solid fa-utensils"></i> Find Restaurants</a>
    </div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <?php $status_class = strtolower(str_replace(' ', '_', $order['status'])); ?>
        <div class="order-card">
            <div class="order-header">
                <div class="order-header-left">
                    <div>
                        <div class="order-meta-label">Order Placed</div>
                        <div class="order-meta-val"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                    </div>
                    <div>
                        <div class="order-meta-label">Total</div>
                        <div class="order-meta-val">$<?= number_format($order['total_amount'], 2) ?></div>
                    </div>
                    <div>
                        <div class="order-meta-label">Order #</div>
                        <div class="order-meta-val"><?= $order['id'] ?></div>
                    </div>
                </div>
                <div class="order-header-right">
                    <a href="index.php?action=order_detail&id=<?= $order['id'] ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">View Details</a>
                </div>
            </div>
            
            <div class="order-body">
                <div>
                    <div class="rest-name"><?= htmlspecialchars($order['restaurant_name']) ?></div>
                    <div class="status-pill status-<?= $status_class ?>"><?= htmlspecialchars(str_replace('_',' ',$order['status'])) ?></div>
                </div>
                <div>
                    <a href="index.php?action=reorder&id=<?= $order['id'] ?>" class="btn"><i class="fa-solid fa-rotate-right"></i> Reorder</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
