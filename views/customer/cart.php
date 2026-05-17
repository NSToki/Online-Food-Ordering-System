<?php
$pageTitle = 'Your Cart | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.cart-container { display: flex; gap: 20px; align-items: flex-start; }
@media (max-width: 768px) { .cart-container { flex-direction: column; } }
.cart-items { flex: 2; background: white; border: 1px solid #ccc; border-radius: 4px; padding: 20px; }
.cart-summary { flex: 1; background: white; border: 1px solid #ccc; border-radius: 4px; padding: 20px; }

.cart-table { width: 100%; border-collapse: collapse; }
.cart-table th { text-align: left; padding-bottom: 10px; border-bottom: 2px solid #ccc; color: #555; font-size: 13px; text-transform: uppercase; }
.cart-table td { padding: 15px 0; border-bottom: 1px solid #eee; vertical-align: middle; }
.cart-table tr:last-child td { border-bottom: none; }

.qty-controls { display: inline-flex; align-items: center; background: #eee; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; }
.qty-btn { background: none; border: none; padding: 5px 10px; cursor: pointer; color: #333; }
.qty-btn:hover { background: #ddd; }
.qty-input { width: 35px; text-align: center; border: none; background: transparent; font-weight: bold; font-size: 14px; }
.qty-input:focus { outline: none; }

.item-remove { color: #c0392b; cursor: pointer; border: none; background: none; padding: 5px; }
.item-remove:hover { color: red; }

.summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #444; }
.summary-total { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #ccc; font-size: 18px; font-weight: bold; color: #333; }
</style>

<h2 class="page-title"><i class="fa-solid fa-cart-shopping"></i> Your Cart</h2>

<?php if (empty($cart)): ?>
    <div class="card empty-state" style="max-width: 600px; margin: 0 auto;">
        <i class="fa-solid fa-basket-shopping"></i>
        <p>Your cart is empty.</p>
        <a href="index.php?action=dashboard" class="btn"><i class="fa-solid fa-utensils"></i> Browse Restaurants</a>
    </div>
<?php else: ?>
    <?php
    $subtotal = 0;
    foreach ($cart as $item) $subtotal += $item['price'] * $item['quantity'];
    $delivery = 50.00;
    $total    = $subtotal + $delivery;
    ?>
    <div class="cart-container">
        <div class="cart-items">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th style="text-align: right;">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                    <tr id="row-<?= $id ?>">
                        <td style="font-weight: 600; color: var(--text);"><?= htmlspecialchars($item['name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty(<?= $id ?>, -1)">-</button>
                                <input type="text" class="qty-input" id="qty-<?= $id ?>" value="<?= $item['quantity'] ?>" readonly>
                                <button class="qty-btn" onclick="updateQty(<?= $id ?>, 1)">+</button>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: var(--brand);" id="sub-<?= $id ?>">
                            $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </td>
                        <td style="text-align: right;">
                            <button class="item-remove" onclick="removeItem(<?= $id ?>)"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <button class="btn btn-outline" onclick="clearCart()"><i class="fa-solid fa-broom"></i> Clear Cart</button>
                <?php if (isset($_SESSION['cart_restaurant_id'])): ?>
                    <a href="index.php?action=restaurant&id=<?= $_SESSION['cart_restaurant_id'] ?>" class="btn" style="background:#555; margin-left: 10px;">Add More Items</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cart-summary">
            <h3 style="margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid var(--border);">Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="summary-subtotal">$<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Delivery Fee</span>
                <span>$<?= number_format($delivery, 2) ?></span>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span id="summary-total" style="color: var(--brand);">$<?= number_format($total, 2) ?></span>
            </div>
            <a href="index.php?action=checkout" class="btn" style="width: 100%; margin-top: 25px; padding: 14px; font-size: 16px;">
                Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
<?php endif; ?>

<script>
function updateQty(id, change) {
    let input = document.getElementById('qty-' + id);
    let newQty = parseInt(input.value) + change;
    if (newQty < 1) return; // Use remove instead
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload(); // Quickest way to sync everything
        }
    };
    xhr.send("action=update_qty&item_id=" + id + "&qty=" + newQty);
}

function removeItem(id) {
    if(!confirm('Remove this item?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) location.reload();
    };
    xhr.send("action=remove_cart&item_id=" + id);
}

function clearCart() {
    if(!confirm('Clear entire cart?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) location.reload();
    };
    xhr.send("action=clear_cart");
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
