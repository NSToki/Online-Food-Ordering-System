<?php require_once 'views/layouts/header.php'; ?>

<div class="page-heading">
    <h1><i class="fa-solid fa-credit-card" style="color:var(--primary);margin-right:.4rem;"></i>Checkout</h1>
    <p>Review your order and complete your purchase</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:2rem;align-items:start;">

    <!-- ── Left: Form ── -->
    <div>
        <form method="POST" action="index.php?action=checkout" id="checkout-form">

            <!-- Delivery Info -->
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-body">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.2rem;">
                        <i class="fa-solid fa-location-dot" style="color:var(--primary);"></i> Delivery Details
                    </h2>
                    <div class="form-group">
                        <label class="form-label" for="delivery_address">Delivery Address</label>
                        <textarea
                            id="delivery_address"
                            name="delivery_address"
                            class="form-control"
                            rows="3"
                            placeholder="Enter your full delivery address…"
                            required
                            style="resize:vertical;"
                        ><?= htmlspecialchars($_POST['delivery_address'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-body">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.2rem;">
                        <i class="fa-solid fa-wallet" style="color:var(--primary);"></i> Payment Method
                    </h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.8rem;">
                        <?php
                        $payments = [
                            'cash_on_delivery' => ['fa-money-bill-wave','Cash on Delivery'],
                            'card'             => ['fa-credit-card',    'Credit / Debit Card'],
                            'mobile_banking'   => ['fa-mobile-screen',  'Mobile Banking'],
                        ];
                        $selected = $_POST['payment_method'] ?? 'cash_on_delivery';
                        foreach ($payments as $val => [$icon, $label]):
                        ?>
                        <label style="
                            display:flex; flex-direction:column; align-items:center; gap:.5rem;
                            padding:1rem; border:2px solid var(--border); border-radius:10px;
                            cursor:pointer; transition:all .2s;
                            <?= $selected === $val ? 'border-color:var(--primary);background:#fff5f2;' : '' ?>
                        " id="pay-label-<?= $val ?>">
                            <input type="radio" name="payment_method" value="<?= $val ?>"
                                   <?= $selected === $val ? 'checked' : '' ?>
                                   style="display:none;" onchange="highlightPayment('<?= $val ?>')">
                            <i class="fa-solid <?= $icon ?>" style="font-size:1.5rem;color:var(--primary);"></i>
                            <span style="font-size:.85rem;font-weight:600;text-align:center;"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" id="place-order-btn" style="font-size:1rem;padding:.9rem;">
                <i class="fa-solid fa-check-circle"></i> Place Order
            </button>
        </form>
    </div>

    <!-- ── Right: Order Summary ── -->
    <div class="card" style="position:sticky;top:80px;">
        <div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.2rem;">
                <i class="fa-solid fa-receipt" style="color:var(--primary);"></i> Order Summary
            </h2>

            <?php if ($restaurant): ?>
                <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:1rem;">
                    <i class="fa-solid fa-store"></i>
                    <?= htmlspecialchars($restaurant['name'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <div style="border-top:2px dashed var(--border);padding-top:1rem;margin-bottom:1rem;">
                <?php foreach ($cart as $id => $item): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:.45rem 0;border-bottom:1px solid var(--border);">
                    <div>
                        <span style="font-weight:600;font-size:.92rem;">
                            <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <small style="display:block;color:var(--text-muted);">
                            Qty: <?= (int)$item['quantity'] ?> &times; $<?= number_format($item['price'], 2) ?>
                        </small>
                    </div>
                    <strong style="color:var(--primary);">
                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </strong>
                </div>
                <?php endforeach; ?>
            </div>

            <table style="width:100%;font-size:.92rem;">
                <tr>
                    <td style="padding:.3rem 0;color:var(--text-muted);">Subtotal</td>
                    <td style="text-align:right;">$<?= number_format($subtotal, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding:.3rem 0;color:var(--text-muted);">Delivery Fee</td>
                    <td style="text-align:right;">$<?= number_format($delivery_fee, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding:.6rem 0 0;font-weight:800;font-size:1.05rem;">Total</td>
                    <td style="padding:.6rem 0 0;text-align:right;font-weight:800;font-size:1.1rem;color:var(--primary);">
                        $<?= number_format($total, 2) ?>
                    </td>
                </tr>
            </table>

            <a href="index.php?action=dashboard" class="btn btn-outline btn-block btn-sm" style="margin-top:1.2rem;">
                <i class="fa-solid fa-plus"></i> Add more items
            </a>
        </div>
    </div>

</div>

<script>
    function highlightPayment(val) {
        const labels = document.querySelectorAll('[id^="pay-label-"]');
        labels.forEach(l => {
            l.style.borderColor = 'var(--border)';
            l.style.background  = '';
        });
        const active = document.getElementById('pay-label-' + val);
        if (active) {
            active.style.borderColor = 'var(--primary)';
            active.style.background  = '#fff5f2';
        }
        // Check corresponding radio
        const radio = active?.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }

    // Confirm before placing order
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const btn = document.getElementById('place-order-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Placing Order…';
        btn.disabled  = true;
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>
