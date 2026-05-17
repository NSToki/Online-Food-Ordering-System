<?php
$pageTitle = 'Order #' . ($order['id'] ?? '') . ' | FoodOrder';
require_once 'views/layouts/header.php';
?>
<style>
.detail-wrap { max-width: 800px; margin: 0 auto; }
.back-link { display: inline-flex; align-items: center; gap: 5px; color: #666; font-size: 14px; margin-bottom: 20px; text-decoration: none; font-weight: bold; }
.back-link:hover { text-decoration: underline; }
.detail-card { background: white; border: 1px solid #ccc; border-radius: 4px; padding: 20px; margin-bottom: 20px; }
.detail-card h3 { font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid var(--brand); display: flex; align-items: center; gap: 8px; color: var(--brand); }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
@media(max-width: 600px){ .info-grid { grid-template-columns: 1fr; } }
.info-item label { display: block; font-size: 11px; font-weight: bold; text-transform: uppercase; color: #666; margin-bottom: 4px; }
.info-item span  { font-size: 14px; font-weight: normal; color: #333; }
.status-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; border: 1px solid #ccc; }
.badge-pending    { background:#fff8e1; color:#b45309; border-color:#ffe0b2; }
.badge-accepted   { background:#e8f5e9; color:#2e7d32; border-color:#c8e6c9; }
.badge-preparing  { background:#f3e5f5; color:#7b1fa2; border-color:#e1bee7; }
.badge-ready      { background:#e3f2fd; color:#1565c0; border-color:#bbdefb; }
.badge-picked_up,.badge-on_the_way { background:#e3f2fd; color:#1565c0; border-color:#bbdefb; }
.badge-delivered  { background:#e8faf5; color:#05875d; border-color:#a7f3d0; }
.badge-cancelled  { background:#ffebee; color:#c62828; border-color:#ffcdd2; }
.items-table { width: 100%; border-collapse: collapse; }
.items-table th { text-align: left; font-size: 12px; text-transform: uppercase; color: #555; padding: 8px; border-bottom: 2px solid #ccc; }
.items-table td { padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 14px; }
.items-table tr:last-child td { border-bottom: none; }
.items-table .t-price { font-weight: bold; color: var(--brand); }
.totals-table { width: 100%; margin-top: 15px; border-collapse: collapse; }
.totals-table td { padding: 6px 8px; font-size: 14px; }
.totals-table .grand td { font-size: 16px; font-weight: bold; border-top: 2px solid #ccc; padding-top: 10px; }
.totals-table td:last-child { text-align: right; font-weight: bold; }

/* Review Form */
.star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 8px; margin-bottom: 15px; }
.star-rating input { display: none; }
.star-rating label { font-size: 24px; color: #ddd; cursor: pointer; }
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label { color: #f39c12; }
</style>

<div class="detail-wrap">
    <a href="index.php?action=order_history" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to My Orders
    </a>

    <?php if (isset($_GET['reviewed'])): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> Review submitted successfully. Thank you!</div>
    <?php endif; ?>

    <!-- LIVE TRACKER COMPONENT -->
    <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'delivered'): ?>
    <div class="detail-card">
        <h3>Live Tracking</h3>
        <div style="padding: 15px; background: #fcfcfc; border: 1px solid #ccc; border-radius: 4px;">
            <div style="margin-bottom: 10px; font-size: 14px;">
                <strong>Order Status:</strong> <span id="order-status-badge" style="color: var(--brand); font-weight: bold;"><?= strtoupper(str_replace('_',' ',$order['status'])) ?></span>
            </div>
            <div style="font-size: 14px;">
                <strong>Estimated Delivery:</strong> <span id="est-time" style="color: var(--brand); font-weight: bold;"><?= $order['estimated_delivery_minutes'] ?></span> minutes
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="detail-card">
        <h3><i class="fa-solid fa-receipt" style="color:var(--brand)"></i> Order #<?= $order['id'] ?> Details</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Restaurant</label>
                <span><?= htmlspecialchars($order['restaurant_name']) ?></span>
            </div>
            <div class="info-item">
                <label>Status</label>
                <span id="status-badge" class="status-badge badge-<?= strtolower(str_replace(' ','_',$order['status'])) ?>">
                    <?= htmlspecialchars(str_replace('_',' ',$order['status'])) ?>
                </span>
            </div>
            <div class="info-item">
                <label>Placed At</label>
                <span><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
            </div>
            <div class="info-item">
                <label>Payment Method</label>
                <span><?= htmlspecialchars($order['payment_method']) ?></span>
            </div>
            <div class="info-item" style="grid-column:1/-1">
                <label>Delivery Address</label>
                <span><?= htmlspecialchars($order['delivery_address']) ?></span>
            </div>
        </div>
        
        <?php if ($order['status'] === 'pending'): ?>
            <div style="margin-top: 25px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="index.php?action=cancel_order&id=<?= $order['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');"><i class="fa-solid fa-xmark"></i> Cancel Order</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="detail-card">
        <h3><i class="fa-solid fa-bag-shopping" style="color:var(--brand)"></i> Items Ordered</h3>
        <table class="items-table">
            <thead>
                <tr><th>Item</th><th>Price</th><th>Qty</th><th style="text-align:right">Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td>$<?= number_format($item['unit_price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td class="t-price" style="text-align:right">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table class="totals-table">
            <tr><td>Subtotal</td><td>$<?= number_format($order['subtotal'], 2) ?></td></tr>
            <tr><td>Delivery Fee</td><td>$<?= number_format($order['delivery_fee'], 2) ?></td></tr>
            <tr class="grand"><td>Total</td><td style="color:var(--brand)">$<?= number_format($order['total_amount'], 2) ?></td></tr>
        </table>
        
        <div style="margin-top:25px; text-align:center;">
            <a href="index.php?action=reorder&id=<?= $order['id'] ?>" class="btn"><i class="fa-solid fa-rotate-right"></i> Re-order these items</a>
        </div>
    </div>

    <?php if ($order['status'] === 'delivered' && !$alreadyReview): ?>
    <div class="detail-card" id="review">
        <h3><i class="fa-solid fa-star" style="color:#f59e0b"></i> Leave a Review</h3>
        <form method="POST" action="index.php?action=submit_review">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <div class="form-group" style="margin-bottom:20px">
                <label>Rating</label>
                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                    <label for="star<?= $i ?>" title="<?= $i ?> Stars"><i class="fa-solid fa-star"></i></label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-group">
                <label>Comment</label>
                <textarea name="comment" class="form-control" rows="4" placeholder="How was the food and delivery experience?" required></textarea>
            </div>
            <button type="submit" class="btn"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
// AJAX Polling logic for Active orders
const orderId = <?= $order['id'] ?>;
const currentStatus = "<?= $order['status'] ?>";

if (currentStatus !== 'cancelled' && currentStatus !== 'delivered') {
    
    function updateTrackerUI(status, minutes) {
        // Update detail status badge
        let badge = document.getElementById('status-badge');
        if (badge) {
            badge.className = 'status-badge badge-' + status.replace(/ /g, '_');
            badge.innerText = status.replace(/_/g, ' ').toUpperCase();
        }
        
        // Update live tracking card status text
        let liveBadge = document.getElementById('order-status-badge');
        if (liveBadge) {
            liveBadge.innerText = status.replace(/_/g, ' ').toUpperCase();
        }
        
        // Update time
        let estTime = document.getElementById('est-time');
        if (estTime) {
            estTime.innerText = minutes;
        }
        
        // Stop polling if delivered or cancelled
        if (status === 'delivered' || status === 'cancelled') {
            location.reload(); // Reload to show review form / remove tracker
        }
    }

    // Initial setup
    updateTrackerUI(currentStatus, <?= $order['estimated_delivery_minutes'] ?>);

    // Poll every 10 seconds
    setInterval(function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "index.php?action=ajax&order_id=" + orderId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    updateTrackerUI(res.order_status, res.estimated_minutes);
                }
            }
        };
        xhr.send();
    }, 10000);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
