<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cart | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; }
  .navbar { background: #ff6b35; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
  .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
  .container { max-width: 700px; margin: 30px auto; padding: 0 20px; }
  h2 { margin-bottom: 20px; color: #333; }
  .card { background: white; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  table { width: 100%; border-collapse: collapse; }
  th { text-align: left; padding: 10px 12px; background: #f8f8f8; font-size: 13px; color: #555; border-bottom: 2px solid #eee; }
  td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
  .totals { margin-top: 20px; text-align: right; font-size: 14px; color: #555; }
  .totals .grand { font-size: 18px; font-weight: bold; color: #ff6b35; margin-top: 6px; }
  .actions { margin-top: 20px; display: flex; gap: 12px; justify-content: flex-end; }
  .btn { padding: 10px 20px; background: #ff6b35; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; }
  .btn:hover { background: #e55a25; }
  .btn-outline { background: white; color: #ff6b35; border: 2px solid #ff6b35; }
  .btn-outline:hover { background: #ff6b35; color: white; }
  .empty { text-align: center; color: #999; padding: 40px; }
  .empty a { color: #ff6b35; }
</style>
</head>
<body>

<div class="navbar">
  <a class="brand" href="index.php?action=dashboard">🍔 FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=dashboard">Restaurants</a>
    <a href="index.php?action=order_history">My Orders</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
</div>

<div class="container">
  <h2>🛒 Your Cart</h2>

  <?php if (empty($cart)): ?>
    <div class="card empty">
      <p>Your cart is empty.</p>
      <p style="margin-top:10px"><a href="index.php?action=dashboard">Browse Restaurants</a></p>
    </div>
  <?php else: ?>
    <?php
    $subtotal = 0;
    foreach ($cart as $item) $subtotal += $item['price'] * $item['quantity'];
    $delivery = 5.00;
    $total    = $subtotal + $delivery;
    ?>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="totals">
        <div>Subtotal: $<?= number_format($subtotal, 2) ?></div>
        <div>Delivery Fee: $<?= number_format($delivery, 2) ?></div>
        <div class="grand">Total: $<?= number_format($total, 2) ?></div>
      </div>

      <div class="actions">
        <a href="index.php?action=dashboard" class="btn btn-outline">Continue Shopping</a>
        <a href="index.php?action=checkout" class="btn">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
