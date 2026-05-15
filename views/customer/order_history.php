<?php require_once 'views/layouts/header.php'; ?>

<div class="page-heading">
    <h1><i class="fa-solid fa-receipt" style="color:var(--primary);margin-right:.4rem;"></i>My Orders</h1>
    <p>Your full order history</p>
</div>

<!-- Flash success message from checkout -->
<?php if (!empty($_SESSION['order_success'])): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= htmlspecialchars($_SESSION['order_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['order_success']); ?>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div style="text-align:center;padding:5rem 2rem;color:var(--text-muted);">
        <i class="fa-solid fa-box-open" style="font-size:4rem;display:block;margin-bottom:1rem;opacity:.3;"></i>
        <h3 style="margin-bottom:.5rem;">No orders yet</h3>
        <p>You haven't placed any orders. Browse restaurants and try something delicious!</p>
        <a href="index.php?action=dashboard" class="btn btn-primary" style="margin-top:1.5rem;">
            <i class="fa-solid fa-store"></i> Browse Restaurants
        </a>
    </div>
<?php else: ?>

    <?php
    $status_badges = [
        'pending'    => ['warning', 'fa-clock',        'Pending'],
        'confirmed'  => ['info',    'fa-check',         'Confirmed'],
        'preparing'  => ['warning', 'fa-fire-burner',   'Preparing'],
        'on_the_way' => ['info',    'fa-motorcycle',    'On the Way'],
        'delivered'  => ['success', 'fa-circle-check',  'Delivered'],
        'cancelled'  => ['danger',  'fa-circle-xmark',  'Cancelled'],
    ];
    ?>

    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <?php foreach ($orders as $order): ?>
        <?php
            $st  = $order['status'] ?? 'pending';
            $badge_info = $status_badges[$st] ?? ['secondary','fa-question','Unknown'];
        ?>
        <div class="card" style="transition:var(--transition);">
            <div class="card-body">
                <!-- Order Header -->
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.8rem;margin-bottom:1.2rem;">
                    <div>
                        <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:.25rem;">
                            Order #<?= (int)$order['id'] ?>
                            <?php if (!empty($order['restaurant_name'])): ?>
                                &mdash; <span style="color:var(--primary);">
                                    <?= htmlspecialchars($order['restaurant_name'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            <?php endif; ?>
                        </h2>
                        <p style="font-size:.85rem;color:var(--text-muted);margin:0;">
                            <i class="fa-solid fa-calendar"></i>
                            <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                        </p>
                    </div>
                    <span class="badge badge-<?= $badge_info[0] ?>" style="font-size:.82rem;padding:.4rem .9rem;">
                        <i class="fa-solid <?= $badge_info[1] ?>"></i>
                        <?= $badge_info[2] ?>
                    </span>
                </div>

                <!-- Order Items -->
                <?php if (!empty($order['items'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:1rem;margin-bottom:1rem;">
                    <p style="font-size:.82rem;font-weight:600;color:var(--text-muted);margin-bottom:.6rem;text-transform:uppercase;letter-spacing:.05em;">Items</p>
                    <div style="display:flex;flex-direction:column;gap:.35rem;">
                        <?php foreach ($order['items'] as $item): ?>
                        <div style="display:flex;justify-content:space-between;font-size:.9rem;">
                            <span>
                                <i class="fa-solid fa-circle" style="font-size:.35rem;color:var(--primary);vertical-align:middle;margin-right:.4rem;"></i>
                                <?= htmlspecialchars($item['item_name'] ?? 'Item', ENT_QUOTES, 'UTF-8') ?>
                                <span style="color:var(--text-muted);">&times; <?= (int)$item['quantity'] ?></span>
                            </span>
                            <span style="font-weight:600;">
                                $<?= number_format($item['unit_price'] * $item['quantity'], 2) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Order Footer -->
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem;border-top:1px solid var(--border);padding-top:1rem;">
                    <div style="font-size:.88rem;color:var(--text-muted);">
                        <i class="fa-solid fa-location-dot"></i>
                        <?= htmlspecialchars($order['delivery_address'], ENT_QUOTES, 'UTF-8') ?>
                        &nbsp;&bull;&nbsp;
                        <i class="fa-solid fa-wallet"></i>
                        <?= ucwords(str_replace('_', ' ', htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'))) ?>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:.82rem;color:var(--text-muted);margin:0;">
                            Subtotal $<?= number_format($order['subtotal'], 2) ?> + Delivery $<?= number_format($order['delivery_fee'], 2) ?>
                        </p>
                        <strong style="font-size:1.1rem;color:var(--primary);">
                            Total: $<?= number_format($order['total_amount'], 2) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
