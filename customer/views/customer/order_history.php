<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; }
  .navbar { background: #ff6b35; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
  .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
  .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
  h2 { margin-bottom: 20px; color: #333; }
  .success { background: #d4edda; color: #155724; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
  .card { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
  table { width: 100%; border-collapse: collapse; font-size: 14px; }
  th { text-align: left; padding: 12px 16px; background: #f8f8f8; color: #555; font-size: 13px; border-bottom: 2px solid #eee; }
  td { padding: 13px 16px; border-bottom: 1px solid #f0f0f0; color: #444; }
  tr:last-child td { border-bottom: none; }
  .status { font-weight: bold; text-transform: uppercase; font-size: 12px; padding: 4px 10px; border-radius: 20px; }
  .status-pending    { background: #fff3cd; color: #856404; }
  .status-preparing  { background: #d1ecf1; color: #0c5460; }
  .status-delivered  { background: #d4edda; color: #155724; }
  .status-cancelled  { background: #f8d7da; color: #721c24; }
  .status-accepted,.status-ready,.status-picked_up,.status-on_the_way { background: #cce5ff; color: #004085; }
  .empty { text-align: center; color: #999; padding: 50px; font-size: 15px; }
  .empty a { color: #ff6b35; }
</style>
</head>
<body>

<div class="navbar">
  <a class="brand" href="index.php?action=dashboard">🍔 FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=dashboard">Restaurants</a>
    <a href="index.php?action=cart">Cart</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
</div>

<div class="container">
  <h2>📋 My Orders</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="success">✅ Order placed successfully! We'll update you on the status.</div>
  <?php endif; ?>

  <?php if (empty($orders)): ?>
    <div class="card empty">
      <p>You have no orders yet.</p>
      <p style="margin-top:10px"><a href="index.php?action=dashboard">Order something now</a></p>
    </div>
  <?php else: ?>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Order #</th>
            <th>Restaurant</th>
            <th>Date</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
          <?php $s = strtolower(str_replace(' ', '_', $order['status'])); ?>
          <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
            <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
            <td>$<?= number_format($order['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($order['payment_method']) ?></td>
            <td><span class="status status-<?= $s ?>"><?= htmlspecialchars($order['status']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
