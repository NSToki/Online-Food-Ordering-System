<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; }
  .navbar { background: #ff6b35; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
  .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
  .container { max-width: 750px; margin: 30px auto; padding: 0 20px; }
  h2 { margin-bottom: 20px; color: #333; }
  .card { background: white; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 20px; }
  .card h3 { font-size: 15px; color: #555; margin-bottom: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
  table { width: 100%; border-collapse: collapse; font-size: 14px; }
  th { text-align: left; padding: 9px 10px; background: #f8f8f8; color: #555; font-size: 13px; border-bottom: 2px solid #eee; }
  td { padding: 10px; border-bottom: 1px solid #f0f0f0; }
  .totals { text-align: right; margin-top: 16px; font-size: 14px; color: #555; }
  .totals .grand { font-size: 18px; font-weight: bold; color: #ff6b35; margin-top: 6px; }
  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 6px; color: #555; }
  .form-group textarea, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: Arial; }
  .form-group textarea:focus, .form-group select:focus { border-color: #ff6b35; outline: none; }
  .btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; font-weight: bold; }
  .btn:hover { background: #218838; }
  .error { background: #fde8e8; color: #c0392b; padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; }
</style>
</head>
<body>

<div class="navbar">
  <a class="brand" href="index.php?action=dashboard">🍔 FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=cart">← Back to Cart</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
</div>

<div class="container">
  <h2>💳 Checkout</h2>

  <!-- Order Summary -->
  <div class="card">
    <h3>Order Summary</h3>
    <table>
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="totals">
      <div>Subtotal: $<?= number_format($subtotal, 2) ?></div>
      <div>Delivery: $<?= number_format($delivery_fee, 2) ?></div>
      <div class="grand">Total: $<?= number_format($total, 2) ?></div>
    </div>
  </div>

  <!-- Delivery & Payment Form -->
  <div class="card">
    <h3>Delivery & Payment Details</h3>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=checkout">
      <div class="form-group">
        <label>Delivery Address</label>
        <textarea name="delivery_address" rows="3" required placeholder="Enter your full delivery address..."></textarea>
      </div>
      <div class="form-group">
        <label>Payment Method</label>
        <select name="payment_method" required>
          <option value="Cash">Cash on Delivery</option>
          <option value="Card">Credit / Debit Card</option>
        </select>
      </div>
      <button type="submit" class="btn">✅ Place Order — $<?= number_format($total, 2) ?></button>
    </form>
  </div>
</div>

</body>
</html>
