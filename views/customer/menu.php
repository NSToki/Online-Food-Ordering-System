<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Menu | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; }
  .navbar { background: #ff6b35; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
  .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
  .nav-links a:hover { text-decoration: underline; }
  .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
  .rest-info { background: white; border-radius: 10px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .rest-info h2 { color: #ff6b35; margin-bottom: 6px; }
  .rest-info p { color: #666; font-size: 14px; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px; }
  .card { background: white; border-radius: 10px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .card h4 { margin-bottom: 6px; color: #333; }
  .card .desc { font-size: 13px; color: #777; margin-bottom: 12px; line-height: 1.5; }
  .card .price { font-size: 15px; font-weight: bold; color: #ff6b35; }
  .btn-add { float: right; padding: 8px 16px; background: #ff6b35; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
  .btn-add:hover { background: #e55a25; }
  .cart-count { font-weight: bold; color: white; }
  .empty { color: #999; text-align: center; margin-top: 40px; }
</style>
</head>
<body>

<div class="navbar">
  <a class="brand" href="index.php?action=dashboard"> FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=cart"> Cart (<span id="cart-count"><?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')) ?></span>)</a>
    <a href="index.php?action=order_history"> My Orders</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
</div>

<div class="container">
  <div class="rest-info">
    <h2><?= htmlspecialchars($restaurant['name'] ?? 'Restaurant') ?></h2>
    <p><?= htmlspecialchars($restaurant['description'] ?? '') ?></p>
  </div>

  <p style="margin-bottom:16px;">
    <a href="index.php?action=dashboard" style="color:#ff6b35;">← Back to Restaurants</a>
    &nbsp;|&nbsp;
    <a href="index.php?action=cart" style="color:#ff6b35;">Go to Cart →</a>
  </p>

  <?php if (empty($menu_items)): ?>
    <p class="empty">No items available for this restaurant.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($menu_items as $item): ?>
      <div class="card">
        <h4><?= htmlspecialchars($item['name']) ?></h4>
        <p class="desc"><?= htmlspecialchars($item['description'] ?? '') ?></p>
        <span class="price">$<?= number_format($item['price'], 2) ?></span>
        <button class="btn-add"
          onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes(htmlspecialchars($item['name'])) ?>', <?= $item['price'] ?>, <?= $restaurant['id'] ?>)">
          + Add
        </button>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
// AJAX Add to Cart using XMLHttpRequest
function addToCart(itemId, itemName, itemPrice, restaurantId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.status === "success") {
                document.getElementById("cart-count").innerText = res.total_items;
                alert(itemName + " added to cart!");
            }
        }
    };

    xhr.send(
        "action=add_cart" +
        "&item_id=" + itemId +
        "&item_name=" + encodeURIComponent(itemName) +
        "&item_price=" + itemPrice +
        "&restaurant_id=" + restaurantId
    );
}
</script>

</body>
</html>
