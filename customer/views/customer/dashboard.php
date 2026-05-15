<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; }
  /* Navbar */
  .navbar { background: #ff6b35; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
  .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
  .nav-links a:hover { text-decoration: underline; }
  /* Content */
  .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
  h2 { margin-bottom: 20px; color: #333; }
  /* Restaurant Grid */
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
  .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .card h3 { color: #ff6b35; margin-bottom: 8px; }
  .card p { font-size: 13px; color: #666; margin-bottom: 5px; }
  .btn { display: inline-block; margin-top: 14px; padding: 9px 18px; background: #ff6b35; color: white; border-radius: 6px; text-decoration: none; font-size: 13px; }
  .btn:hover { background: #e55a25; }
  .empty { color: #999; font-size: 15px; text-align: center; margin-top: 60px; }
</style>
</head>
<body>

<div class="navbar">
  <a class="brand" href="index.php?action=dashboard">🍔 FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=cart">🛒 Cart (<?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')) ?>)</a>
    <a href="index.php?action=order_history">📋 My Orders</a>
    <a href="index.php?action=logout">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
  </div>
</div>

<div class="container">
  <h2>Available Restaurants</h2>

  <?php if (empty($restaurants)): ?>
    <p class="empty">No restaurants available right now.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($restaurants as $r): ?>
      <div class="card">
        <h3><?= htmlspecialchars($r['name']) ?></h3>
        <p>🍽 <?= htmlspecialchars($r['cuisine_type'] ?? 'Various') ?></p>
        <p>📍 <?= htmlspecialchars($r['city']) ?></p>
        <?php if (!empty($r['opening_hours'])): ?>
          <p>🕐 <?= htmlspecialchars($r['opening_hours']) ?></p>
        <?php endif; ?>
        <a href="index.php?action=menu&id=<?= $r['id'] ?>" class="btn">View Menu</a>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
