<?php require_once 'views/layouts/header.php'; ?>

<div class="page-heading">
    <h1><i class="fa-solid fa-cart-shopping" style="color:var(--primary);margin-right:.4rem;"></i>Your Cart</h1>
    <p>Review items before checking out</p>
</div>

<?php $cart = $_SESSION['cart'] ?? []; ?>

<?php if (empty($cart)): ?>
    <div style="text-align:center;padding:5rem 2rem;color:var(--text-muted);">
        <i class="fa-solid fa-cart-shopping" style="font-size:4rem;display:block;margin-bottom:1rem;opacity:.25;"></i>
        <h3 style="margin-bottom:.5rem;">Your cart is empty</h3>
        <p>Add some delicious items from a restaurant menu to get started.</p>
        <a href="index.php?action=dashboard" class="btn btn-primary" style="margin-top:1.5rem;">
            <i class="fa-solid fa-store"></i> Browse Restaurants
        </a>
    </div>
<?php else: ?>

<?php
$delivery_fee = 2.99;
$subtotal     = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
$total        = $subtotal + $delivery_fee;
?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:start;">

    <!-- ── Cart Items ── -->
    <div class="card">
        <div class="card-body">
            <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.2rem;">
                <i class="fa-solid fa-list" style="color:var(--primary);"></i> Cart Items
            </h2>
            <?php foreach ($cart as $id => $item): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:.9rem 0;border-bottom:1px solid var(--border);"
                 id="cart-row-<?= (int)$id ?>">
                <div>
                    <strong style="display:block;"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <small style="color:var(--text-muted);">
                        $<?= number_format($item['price'], 2) ?> each
                    </small>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <span style="font-weight:700;font-size:.95rem;">
                        &times; <?= (int)$item['quantity'] ?>
                    </span>
                    <strong style="color:var(--primary);">
                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </strong>
                    <button
                        class="btn btn-danger btn-sm"
                        onclick="removeItem(<?= (int)$id ?>, this)"
                        title="Remove item"
                        id="remove-<?= (int)$id ?>"
                    ><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; ?>

            <div style="display:flex;justify-content:flex-end;margin-top:1rem;">
                <button class="btn btn-outline btn-sm" onclick="clearCart(this)" id="clear-cart-btn">
                    <i class="fa-solid fa-broom"></i> Clear Cart
                </button>
            </div>
        </div>
    </div>

    <!-- ── Summary ── -->
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="card">
            <div class="card-body">
                <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.2rem;">
                    <i class="fa-solid fa-calculator" style="color:var(--primary);"></i> Summary
                </h2>
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
                        <td style="padding:.8rem 0 0;font-weight:800;font-size:1.05rem;">Total</td>
                        <td style="padding:.8rem 0 0;text-align:right;font-weight:800;font-size:1.1rem;color:var(--primary);">
                            $<?= number_format($total, 2) ?>
                        </td>
                    </tr>
                </table>
                <a href="index.php?action=checkout" class="btn btn-primary btn-block" style="margin-top:1.2rem;">
                    <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
                </a>
                <a href="index.php?action=dashboard" class="btn btn-outline btn-block btn-sm" style="margin-top:.6rem;">
                    <i class="fa-solid fa-plus"></i> Add more items
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    async function removeItem(id, btn) {
        btn.disabled   = true;
        btn.innerHTML  = '<i class="fa-solid fa-spinner fa-spin"></i>';
        const res = await ajaxPost({ action: 'remove_cart', item_id: id });
        if (res.status === 'success') {
            const row = document.getElementById('cart-row-' + id);
            if (row) {
                row.style.opacity    = '0';
                row.style.transition = 'opacity .3s';
                setTimeout(() => { row.remove(); location.reload(); }, 350);
            }
            updateCartBadge(res.total_items);
        }
    }

    async function clearCart(btn) {
        if (!confirm('Clear your entire cart?')) return;
        btn.disabled   = true;
        btn.innerHTML  = '<i class="fa-solid fa-spinner fa-spin"></i> Clearing…';
        await ajaxPost({ action: 'clear_cart' });
        location.reload();
    }
</script>

<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
