<?php
$pageTitle = $restaurant['name'] . ' | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.rest-header { background: white; border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 25px; display: flex; gap: 20px; align-items: center; position: relative; }
.rest-header img { width: 100px; height: 100px; border-radius: 4px; object-fit: cover; border: 1px solid #ddd; }
.rest-info { flex: 1; }
.rest-info h1 { font-size: 24px; margin-bottom: 5px; color: var(--brand); }
.rest-info p { color: #666; margin-bottom: 10px; font-size: 14px; line-height: 1.4; }
.rest-badges { display: flex; gap: 10px; }
.badge { background: #eee; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #333; display: flex; align-items: center; gap: 5px; }
.badge i { color: var(--brand); }
.fav-btn { position: absolute; top: 20px; right: 20px; background: white; border: 1px solid #ccc; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #aaa; font-size: 14px; }
.fav-btn.active { color: red; }

.menu-section { margin-bottom: 30px; }
.category-title { font-size: 18px; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 2px solid var(--brand); color: var(--brand); }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }
.menu-item { background: white; border: 1px solid #ddd; border-radius: 4px; padding: 15px; display: flex; gap: 12px; position: relative; }
.item-img { width: 70px; height: 70px; border-radius: 4px; object-fit: cover; background: #eee; }
.item-details { flex: 1; display: flex; flex-direction: column; }
.item-name { font-weight: bold; font-size: 15px; margin-bottom: 4px; color: #333; }
.item-desc { font-size: 12px; color: #666; margin-bottom: 8px; line-height: 1.3; }
.item-price-row { margin-top: auto; display: flex; justify-content: space-between; align-items: center; }
.price-tag { font-weight: bold; color: var(--brand); font-size: 15px; }
.old-price { text-decoration: line-through; color: #888; font-size: 12px; margin-left: 6px; font-weight: normal; }
.discount-text { color: #c0392b; font-weight: bold; font-size: 11px; margin-left: 8px; }
.add-btn { background: var(--brand); color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; }
.add-btn:hover { background: var(--brand-dark); }

/* Reviews */
.review-card { background: white; padding: 15px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #ddd; }
.reviewer-name { font-weight: bold; font-size: 14px; display: flex; justify-content: space-between; margin-bottom: 5px; }
.review-date { font-size: 11px; color: #888; font-weight: normal; }
.review-rating { color: #f39c12; font-size: 12px; margin-bottom: 8px; }
.review-text { font-size: 13px; color: #444; line-height: 1.4; }
</style>

<div class="rest-header">
    <img src="<?= (!empty($restaurant['logo_path']) && file_exists('assets/uploads/'.$restaurant['logo_path'])) ? 'assets/uploads/'.$restaurant['logo_path'] : 'https://placehold.co/200x200?text='.urlencode($restaurant['name']) ?>" alt="Logo">
    <div class="rest-info">
        <h1><?= htmlspecialchars($restaurant['name']) ?></h1>
        <p><?= htmlspecialchars($restaurant['description'] ?? 'No description available.') ?></p>
        <div class="rest-badges">
            <div class="badge"><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($restaurant['cuisine_type'] ?? 'Various') ?></div>
            <div class="badge"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($restaurant['city']) ?></div>
            <div class="badge"><i class="fa-solid fa-star" style="color:#f59e0b"></i> <?= $restaurant['avg_rating'] ? number_format($restaurant['avg_rating'], 1) : 'New' ?></div>
        </div>
    </div>
    <button class="fav-btn <?= $is_saved ? 'active' : '' ?>" onclick="toggleFav(this, <?= $restaurant['id'] ?>)">
        <i class="fa-solid fa-heart"></i>
    </button>
</div>

<!-- Group Menu Items by Category -->
<?php
$grouped = [];
foreach ($menu_items as $item) {
    $cid = $item['category_id'] ?? 0;
    $grouped[$cid][] = $item;
}
?>

<?php if (empty($menu_items)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-book-open"></i>
        <p>This restaurant hasn't added any menu items yet.</p>
    </div>
<?php else: ?>
    <?php foreach ($categories as $cat): ?>
        <?php if (!empty($grouped[$cat['id']])): ?>
            <div class="menu-section">
                <h3 class="category-title"><?= htmlspecialchars($cat['name']) ?></h3>
                <div class="menu-grid">
                    <?php foreach ($grouped[$cat['id']] as $item): ?>
                        <div class="menu-item">
                            <?php if (!empty($item['image_path']) && file_exists('assets/uploads/'.$item['image_path'])): ?>
                                <img src="assets/uploads/<?= $item['image_path'] ?>" class="item-img" alt="Item">
                            <?php else: ?>
                                <img src="https://placehold.co/100x100?text=<?= urlencode($item['name']) ?>" class="item-img" alt="Item">
                            <?php endif; ?>
                            
                            <div class="item-details">
                                <div class="item-name">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <?php if ($item['discount_pct'] > 0): ?>
                                        <span class="discount-text">(<?= $item['discount_pct'] ?>% Off!)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                                <div class="item-price-row">
                                    <div class="price-tag">
                                        $<?= number_format($item['discounted_price'], 2) ?>
                                        <?php if ($item['discount_pct'] > 0): ?>
                                            <span class="old-price">$<?= number_format($item['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="add-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes(htmlspecialchars($item['name'])) ?>', <?= $item['discounted_price'] ?>, <?= $restaurant['id'] ?>)">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <!-- Items without a category -->
    <?php if (!empty($grouped[0])): ?>
        <div class="menu-section">
            <h3 class="category-title">Other Items</h3>
            <div class="menu-grid">
                <?php foreach ($grouped[0] as $item): ?>
                    <div class="menu-item">
                            <?php if (!empty($item['image_path']) && file_exists('assets/uploads/'.$item['image_path'])): ?>
                                <img src="assets/uploads/<?= $item['image_path'] ?>" class="item-img" alt="Item">
                            <?php else: ?>
                                <img src="https://placehold.co/100x100?text=<?= urlencode($item['name']) ?>" class="item-img" alt="Item">
                            <?php endif; ?>
                            
                            <div class="item-details">
                                <div class="item-name">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <?php if ($item['discount_pct'] > 0): ?>
                                        <span class="discount-text">(<?= $item['discount_pct'] ?>% Off!)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                                <div class="item-price-row">
                                    <div class="price-tag">
                                        $<?= number_format($item['discounted_price'], 2) ?>
                                        <?php if ($item['discount_pct'] > 0): ?>
                                            <span class="old-price">$<?= number_format($item['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="add-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes(htmlspecialchars($item['name'])) ?>', <?= $item['discounted_price'] ?>, <?= $restaurant['id'] ?>)">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Reviews Section -->
<h3 class="category-title" style="margin-top: 50px;"><i class="fa-solid fa-comments"></i> Customer Reviews</h3>
<?php if (empty($reviews)): ?>
    <p style="color:var(--text-muted)">No reviews yet for this restaurant.</p>
<?php else: ?>
    <div style="max-width: 800px;">
        <?php foreach ($reviews as $rev): ?>
            <div class="review-card">
                <div class="reviewer-name">
                    <?= htmlspecialchars($rev['customer_name']) ?>
                    <span class="review-date"><?= date('M d, Y', strtotime($rev['created_at'])) ?></span>
                </div>
                <div class="review-rating">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fa-<?= $i <= $rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
                <div class="review-text">
                    <?= nl2br(htmlspecialchars($rev['comment'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function addToCart(itemId, itemName, itemPrice, restaurantId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.status === "success") {
                document.getElementById("nav-cart-count").innerText = res.total_items;
                // Optional: Show a nice toast notification here instead of alert
                alert(itemName + " added to cart!");
            }
        }
    };
    xhr.send("action=add_cart&item_id="+itemId+"&item_name="+encodeURIComponent(itemName)+"&item_price="+itemPrice+"&restaurant_id="+restaurantId);
}

function toggleFav(btn, id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.status === 'success') {
                if (res.action === 'saved') btn.classList.add('active');
                else btn.classList.remove('active');
            }
        }
    };
    xhr.send("action=toggle_save&restaurant_id=" + id);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
