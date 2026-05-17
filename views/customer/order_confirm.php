<?php
$pageTitle = 'Order Confirmed | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.confirm-box { max-width: 600px; margin: 40px auto; text-align: center; background: var(--card-bg); padding: 50px 30px; border-radius: var(--radius); box-shadow: var(--shadow); border-top: 6px solid #27ae60; }
.success-icon { font-size: 70px; color: #27ae60; margin-bottom: 20px; }
.order-number { font-size: 24px; font-weight: 800; color: var(--text); margin: 15px 0; }
.info-text { color: var(--text-muted); font-size: 16px; line-height: 1.6; margin-bottom: 30px; }
.tracker { background: #f4f7f6; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: left; }
.tracker-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; }
.tracker-row strong { color: var(--text); }
.btn-group { display: flex; gap: 15px; justify-content: center; }
</style>

<div class="confirm-box">
    <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
    <h2 style="margin-bottom: 10px;">Order Placed Successfully!</h2>
    <div class="order-number">Order #<?= $order['id'] ?></div>
    
    <p class="info-text">
        Thank you for your order from <strong><?= htmlspecialchars($order['restaurant_name']) ?></strong>.<br>
        We've received your order and the restaurant will begin preparing it shortly.
    </p>

    <div class="tracker">
        <div class="tracker-row">
            <span>Status:</span>
            <strong><i class="fa-solid fa-clock" style="color:var(--brand)"></i> Pending</strong>
        </div>
        <div class="tracker-row">
            <span>Estimated Delivery:</span>
            <strong>~<?= $order['estimated_delivery_minutes'] ?> minutes</strong>
        </div>
        <div class="tracker-row">
            <span>Payment Method:</span>
            <strong><?= htmlspecialchars($order['payment_method']) ?></strong>
        </div>
    </div>

    <div class="btn-group">
        <a href="index.php?action=order_detail&id=<?= $order['id'] ?>" class="btn"><i class="fa-solid fa-magnifying-glass"></i> Track Order</a>
        <a href="index.php?action=dashboard" class="btn btn-outline"><i class="fa-solid fa-house"></i> Home</a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
