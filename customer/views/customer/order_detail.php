<?php
$pageTitle = 'Order #' . ($order['id'] ?? '');
require_once 'views/layouts/header.php';
?>
<style>
.detail-wrap { max-width:800px;margin:0 auto; }
.back-link { display:inline-flex;align-items:center;gap:.5rem;color:var(--text-muted);font-size:.9rem;margin-bottom:1.5rem;transition:color var(--transition); }
.back-link:hover { color:var(--brand); }
.detail-card { background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);padding:1.75rem;margin-bottom:1.5rem; }
.detail-card h2 { font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:2px solid var(--border);display:flex;align-items:center;gap:.5rem; }
.info-grid { display:grid;grid-template-columns:1fr 1fr;gap:.75rem; }
@media(max-width:600px){ .info-grid { grid-template-columns:1fr; } }
.info-item label { display:block;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:.25rem; }
.info-item span  { font-size:.95rem;font-weight:600; }
.status-badge { display:inline-block;padding:.35rem 1rem;border-radius:50px;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em; }
.badge-pending    { background:#fff8e1;color:#b45309; }
.badge-accepted   { background:#e8f5e9;color:#2e7d32; }
.badge-preparing  { background:#ede7f6;color:#512da8; }
.badge-ready      { background:#e3f2fd;color:#1565c0; }
.badge-picked_up,.badge-on_the_way { background:#e3f2fd;color:#1565c0; }
.badge-delivered  { background:#e8faf5;color:#05875d; }
.badge-cancelled  { background:#ffebee;color:#c62828; }
.items-table { width:100%;border-collapse:collapse; }
.items-table th { text-align:left;font-size:.82rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);padding:.6rem .5rem;border-bottom:2px solid var(--border); }
.items-table td { padding:.7rem .5rem;border-bottom:1px solid var(--border);font-size:.93rem; }
.items-table tr:last-child td { border-bottom:none; }
.items-table .t-price { font-weight:700;color:var(--brand); }
.totals-table { width:100%;margin-top:1rem;border-collapse:collapse; }
.totals-table td { padding:.4rem .5rem;font-size:.93rem; }
.totals-table .grand td { font-size:1.1rem;font-weight:800;border-top:2px solid var(--border);padding-top:.75rem; }
.totals-table td:last-child { text-align:right;font-weight:700; }
/* Review Form */
.star-rating { display:flex;flex-direction:row-reverse;justify-content:flex-end;gap:.25rem;margin-bottom:.75rem; }
.star-rating input { display:none; }
.star-rating label { font-size:1.6rem;color:#ccc;cursor:pointer;transition:color .15s; }
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label { color:#f59e0b; }
</style>

<div class="detail-wrap">
    <a href="index.php?action=order_history" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to My Orders
    </a>

    <div class="detail-card">
        <h2><i class="fa-solid fa-receipt" style="color:var(--brand)"></i> Order #<?= $order['id'] ?> Details</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Restaurant</label>
                <span><?= htmlspecialchars($order['restaurant_name']) ?></span>
            </div>
            <div class="info-item">
                <label>Status</label>
                <span class="status-badge badge-<?= strtolower(str_replace(' ','_',$order['status'])) ?>">
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
    </div>

    <div class="detail-card">
        <h2><i class="fa-solid fa-bag-shopping" style="color:var(--brand)"></i> Items Ordered</h2>
        <table class="items-table">
            <thead>
                <tr><th>Item</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td>$<?= number_format($item['unit_price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td class="t-price">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table class="totals-table">
            <tr><td>Subtotal</td><td>$<?= number_format($order['subtotal'], 2) ?></td></tr>
            <tr><td>Delivery Fee</td><td>$<?= number_format($order['delivery_fee'], 2) ?></td></tr>
            <tr class="grand"><td>Total</td><td style="color:var(--brand)">$<?= number_format($order['total_amount'], 2) ?></td></tr>
        </table>
    </div>

    <?php if ($order['status'] === 'delivered'): ?>
    <div class="detail-card" id="review">
        <h2><i class="fa-solid fa-star" style="color:#f59e0b"></i> Leave a Review</h2>
        <?php if (isset($reviewSuccess)): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Review submitted. Thank you!</div>
        <?php elseif (!isset($alreadyReviewed)): ?>
        <form method="POST" action="index.php?action=submit_review">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <div class="form-group" style="margin-bottom:1rem">
                <label style="font-weight:600;font-size:.88rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Rating</label>
                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                    <label for="star<?= $i ?>"><i class="fa-solid fa-star"></i></label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-group">
                <label style="font-weight:600;font-size:.88rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Comment</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience…"></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
        </form>
        <?php else: ?>
            <div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> You have already reviewed this order.</div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
