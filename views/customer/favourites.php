<?php
$pageTitle = 'Favourites | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
/* Re-use grid from dashboard */
.rest-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
.rest-card { background: var(--card-bg); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); display: flex; flex-direction: column; }
.rest-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
.rest-img { height: 160px; background: #eee; position: relative; }
.rest-img img { width: 100%; height: 100%; object-fit: cover; }
.fav-btn { position: absolute; top: 10px; right: 10px; background: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: var(--transition); border: none; }
.fav-btn.active { color: #e74c3c; }
.fav-btn:hover { transform: scale(1.1); }
.rest-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.rest-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--text); }
.rest-meta { display: flex; align-items: center; gap: 15px; font-size: 13px; color: var(--text-muted); margin-bottom: 12px; }
.rest-meta i { color: var(--brand); }
.rating { display: inline-flex; align-items: center; gap: 4px; background: #fff8e1; color: #f59e0b; padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 13px; }
.rest-footer { margin-top: auto; padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
</style>

<h2 class="page-title"><i class="fa-solid fa-heart" style="color:#e74c3c;"></i> My Favourites</h2>

<?php if (empty($restaurants)): ?>
    <div class="card empty-state" style="max-width: 600px; margin: 0 auto;">
        <i class="fa-regular fa-heart"></i>
        <p>You haven't saved any restaurants yet.</p>
        <a href="index.php?action=dashboard" class="btn"><i class="fa-solid fa-magnifying-glass"></i> Browse Restaurants</a>
    </div>
<?php else: ?>
    <div class="rest-grid">
        <?php foreach ($restaurants as $r): ?>
        <div class="rest-card" id="rest-<?= $r['id'] ?>">
            <div class="rest-img">
                <img src="<?= $r['logo_path'] ? 'assets/uploads/'.$r['logo_path'] : 'https://placehold.co/400x200?text=No+Image' ?>" alt="<?= htmlspecialchars($r['name']) ?>">
                <button class="fav-btn active" onclick="toggleFav(this, <?= $r['id'] ?>)" title="Remove from Favourites">
                    <i class="fa-solid fa-heart"></i>
                </button>
            </div>
            <div class="rest-body">
                <div class="rest-title"><?= htmlspecialchars($r['name']) ?></div>
                <div class="rest-meta">
                    <span><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($r['cuisine_type'] ?? 'Various') ?></span>
                    <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($r['city']) ?></span>
                </div>
                
                <div class="rest-footer">
                    <div class="rating">
                        <i class="fa-solid fa-star"></i> <?= $r['avg_rating'] ? number_format($r['avg_rating'], 1) : 'New' ?>
                    </div>
                    <a href="index.php?action=restaurant&id=<?= $r['id'] ?>" class="btn btn-outline" style="padding:6px 12px; font-size:13px;">View Menu</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function toggleFav(btn, id) {
    if(!confirm('Remove from favourites?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=ajax", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Remove the card from the DOM
            var card = document.getElementById('rest-' + id);
            if (card) {
                card.style.transition = "opacity 0.3s";
                card.style.opacity = "0";
                setTimeout(() => {
                    card.remove();
                    // If no cards left, reload to show empty state
                    if (document.querySelectorAll('.rest-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        }
    };
    xhr.send("action=toggle_save&restaurant_id=" + id);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
