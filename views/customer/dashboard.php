<?php
$pageTitle = 'Dashboard | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.search-bar { background: #f0f0f0; border: 1px solid #ccc; padding: 15px; border-radius: 4px; margin-bottom: 25px; display: flex; gap: 10px; align-items: center; }
.search-bar .form-group { margin-bottom: 0; flex: 1; }
.rest-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
.rest-card { background: white; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; }
.rest-img { height: 140px; background: #eee; position: relative; }
.rest-img img { width: 100%; height: 100%; object-fit: cover; }
.fav-btn { position: absolute; top: 10px; right: 10px; background: white; border: 1px solid #ccc; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #aaa; }
.fav-btn.active { color: red; }
.rest-body { padding: 15px; flex: 1; display: flex; flex-direction: column; }
.rest-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; color: #333; }
.rest-meta { display: flex; align-items: center; gap: 10px; font-size: 12px; color: #666; margin-bottom: 8px; }
.rest-meta i { color: var(--brand); }
.rating { font-size: 12px; color: #f39c12; font-weight: bold; }
.rest-footer { margin-top: auto; padding-top: 10px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
</style>

<h2 class="page-title"><i class="fa-solid fa-compass"></i> Discover Restaurants</h2>

<form method="GET" action="index.php" class="search-bar">
    <input type="hidden" name="action" value="dashboard">
    <div class="form-group">
        <label>Search</label>
        <div style="position:relative">
            <i class="fa-solid fa-search" style="position:absolute;left:14px;top:12px;color:#aaa"></i>
            <input type="text" name="search" class="form-control" placeholder="Restaurant name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="padding-left:40px">
        </div>
    </div>
    <div class="form-group">
        <label>Cuisine</label>
        <select name="cuisine" class="form-control">
            <option value="">All Cuisines</option>
            <?php foreach ($cuisine_types as $c): if(!$c['cuisine_type']) continue; ?>
                <option value="<?= htmlspecialchars($c['cuisine_type']) ?>" <?= (($_GET['cuisine']??'') === $c['cuisine_type']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['cuisine_type']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>City</label>
        <select name="city" class="form-control">
            <option value="">All Cities</option>
            <?php foreach ($cities as $c): if(!$c['city']) continue; ?>
                <option value="<?= htmlspecialchars($c['city']) ?>" <?= (($_GET['city']??'') === $c['city']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['city']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn" style="height: 42px;"><i class="fa-solid fa-filter"></i> Filter</button>
    <?php if(!empty($_GET['search']) || !empty($_GET['cuisine']) || !empty($_GET['city'])): ?>
        <a href="index.php?action=dashboard" class="btn btn-outline" style="height: 42px;">Clear</a>
    <?php endif; ?>
</form>

<?php if (empty($restaurants)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-store-slash"></i>
        <p>No restaurants found matching your criteria.</p>
        <a href="index.php?action=dashboard" class="btn">View All Restaurants</a>
    </div>
<?php else: ?>
    <div class="rest-grid">
        <?php foreach ($restaurants as $r): ?>
        <div class="rest-card">
            <div class="rest-img">
                <img src="<?= (!empty($r['logo_path']) && file_exists('assets/uploads/'.$r['logo_path'])) ? 'assets/uploads/'.$r['logo_path'] : 'https://placehold.co/400x200?text='.urlencode($r['name']) ?>" alt="<?= htmlspecialchars($r['name']) ?>">
                <button class="fav-btn <?= in_array($r['id'], $savedIds) ? 'active' : '' ?>" onclick="toggleFav(this, <?= $r['id'] ?>)" title="Save to Favourites">
                    <i class="fa-solid fa-heart"></i>
                </button>
            </div>
            <div class="rest-body">
                <div class="rest-title"><?= htmlspecialchars($r['name']) ?></div>
                <div class="rest-meta">
                    <span><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($r['cuisine_type'] ?? 'Various') ?></span>
                    <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($r['city']) ?></span>
                </div>
                <?php if (!empty($r['opening_hours'])): ?>
                    <div style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                        <i class="fa-regular fa-clock" style="color:var(--brand)"></i> <?= htmlspecialchars($r['opening_hours']) ?>
                    </div>
                <?php endif; ?>
                
                <div class="rest-footer">
                    <div class="rating">
                        <i class="fa-solid fa-star"></i> 
                        <?= $r['avg_rating'] ? number_format($r['avg_rating'], 1) : 'New' ?> 
                        <span style="color:#aaa; font-weight:normal; font-size:12px">(<?= $r['review_count'] ?>)</span>
                    </div>
                    <a href="index.php?action=restaurant&id=<?= $r['id'] ?>" class="btn">View Menu <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function toggleFav(btn, id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.status === 'success') {
                if (res.action === 'saved') {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }
        }
    };
    xhr.send("action=toggle_save&restaurant_id=" + id);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
