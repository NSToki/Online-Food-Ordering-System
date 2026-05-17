<?php
$pageTitle = 'Checkout | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.checkout-container { display: flex; gap: 30px; align-items: flex-start; }
@media (max-width: 768px) { .checkout-container { flex-direction: column; } }
.checkout-form { flex: 2; background: var(--card-bg); border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow); }
.checkout-summary { flex: 1; background: var(--card-bg); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); position: sticky; top: 90px; }

h3 { margin-bottom: 20px; font-size: 18px; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 10px; }
.saved-address-card { border: 1px solid var(--border); padding: 15px; border-radius: 6px; margin-bottom: 15px; cursor: pointer; transition: var(--transition); display: flex; align-items: flex-start; gap: 10px; }
.saved-address-card:hover { border-color: var(--brand); background: rgba(255,107,53,0.02); }
.saved-address-card input[type="radio"] { margin-top: 4px; accent-color: var(--brand); }
.address-label { font-weight: 700; color: var(--text); margin-bottom: 4px; display: block; }
.address-text { font-size: 14px; color: var(--text-muted); line-height: 1.4; }

.payment-options { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
.payment-card { border: 1px solid var(--border); padding: 15px; border-radius: 6px; cursor: pointer; text-align: center; transition: var(--transition); }
.payment-card:hover { border-color: var(--brand); }
.payment-card input[type="radio"] { display: none; }
.payment-card input[type="radio"]:checked + .pay-content { color: var(--brand); font-weight: bold; }
.payment-card input[type="radio"]:checked ~ .pay-icon { color: var(--brand); }
.pay-icon { font-size: 24px; margin-bottom: 8px; color: #888; transition: var(--transition); }

.summary-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-muted); }
.summary-total { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid var(--border); font-size: 18px; font-weight: 800; color: var(--text); }
</style>

<h2 class="page-title"><i class="fa-solid fa-credit-card"></i> Checkout</h2>

<div class="checkout-container">
    <div class="checkout-form">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=checkout" id="checkoutForm">
            
            <h3>1. Delivery Address</h3>
            
            <?php if (!empty($addresses)): ?>
                <div style="margin-bottom: 25px;">
                    <p style="margin-bottom: 10px; font-size: 14px; font-weight: 600;">Select a saved address:</p>
                    <?php foreach ($addresses as $idx => $addr): ?>
                        <label class="saved-address-card" onclick="document.getElementById('new_addr').value = this.querySelector('.full-addr').value;">
                            <input type="radio" name="address_selector" <?= $addr['is_default'] ? 'checked' : '' ?>>
                            <div>
                                <span class="address-label"><?= htmlspecialchars($addr['label']) ?> <?= $addr['is_default'] ? '<span style="color:var(--brand);font-size:12px;margin-left:5px;">(Default)</span>' : '' ?></span>
                                <span class="address-text"><?= htmlspecialchars($addr['address_line']) ?>, <?= htmlspecialchars($addr['city']) ?></span>
                            </div>
                            <input type="hidden" class="full-addr" value="<?= htmlspecialchars($addr['address_line'] . ', ' . $addr['city']) ?>">
                        </label>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center; margin-bottom: 15px; color: #aaa;">— OR —</div>
            <?php endif; ?>

            <div class="form-group">
                <label>Deliver To (Editable)</label>
                <?php
                $default_addr_str = '';
                if (!empty($addresses)) {
                    foreach($addresses as $a) {
                        if($a['is_default']) {
                            $default_addr_str = $a['address_line'] . ', ' . $a['city'];
                            break;
                        }
                    }
                    if (!$default_addr_str) $default_addr_str = $addresses[0]['address_line'] . ', ' . $addresses[0]['city'];
                }
                ?>
                <textarea name="delivery_address" id="new_addr" class="form-control" rows="3" required placeholder="Enter full delivery address..."><?= htmlspecialchars($default_addr_str) ?></textarea>
            </div>

            <h3 style="margin-top: 40px;">2. Payment Method</h3>
            <div class="payment-options">
                <label class="payment-card">
                    <div class="pay-icon"><i class="fa-solid fa-money-bill-1-wave"></i></div>
                    <input type="radio" name="payment_method" value="Cash" checked>
                    <span class="pay-content">Cash on Delivery</span>
                </label>
                <label class="payment-card">
                    <div class="pay-icon"><i class="fa-regular fa-credit-card"></i></div>
                    <input type="radio" name="payment_method" value="Card">
                    <span class="pay-content">Credit / Debit Card</span>
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 16px; font-size: 16px; margin-top: 10px;">
                <i class="fa-solid fa-lock"></i> Place Order — $<?= number_format($total, 2) ?>
            </button>
        </form>
    </div>

    <div class="checkout-summary">
        <h3>Order Summary</h3>
        <div style="margin-bottom: 20px;">
            <?php foreach ($cart as $item): ?>
                <div class="summary-item">
                    <span><span style="color:var(--brand);font-weight:bold;margin-right:8px;"><?= $item['quantity'] ?>x</span> <?= htmlspecialchars($item['name']) ?></span>
                    <span style="font-weight:600; color:var(--text);">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="summary-item">
            <span>Subtotal</span>
            <span style="font-weight:600; color:var(--text);">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="summary-item">
            <span>Delivery Fee</span>
            <span style="font-weight:600; color:var(--text);">$<?= number_format($delivery_fee, 2) ?></span>
        </div>
        <div class="summary-total">
            <span>Total To Pay</span>
            <span style="color:var(--brand);">$<?= number_format($total, 2) ?></span>
        </div>
    </div>
</div>

<script>
// If a user clicks a saved address, update the textarea. This is handled by the inline onclick handler on the label.
</script>

<?php require_once 'views/layouts/footer.php'; ?>
