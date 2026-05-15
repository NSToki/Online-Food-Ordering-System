<?php require_once 'views/layouts/header.php'; ?>

<!-- ── Hero Banner ── -->
<section style="
    background: linear-gradient(135deg,#ff6b35,#ff8c60 40%,#ffc947);
    border-radius:16px; padding:2.5rem 2rem; margin-bottom:2rem;
    display:flex; align-items:center; justify-content:space-between; gap:1rem;
    color:#fff; overflow:hidden; position:relative;">
    <div>
        <h1 style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:800;margin-bottom:.5rem;">
            Hello, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?>! 👋
        </h1>
        <p style="opacity:.92;font-size:1.05rem;">What are you craving today? Browse our top restaurants.</p>
    </div>
    <i class="fa-solid fa-bowl-food" style="font-size:6rem;opacity:.2;position:absolute;right:2rem;"></i>
</section>

<!-- ── Search Bar ── -->
<div style="margin-bottom:1.8rem;">
    <input
        type="search"
        id="restaurant-search"
        class="form-control"
        placeholder="🔍  Search restaurants..."
        style="max-width:480px; font-size:1rem; padding:.7rem 1.2rem;"
        oninput="filterRestaurants(this.value)"
    >
</div>

<!-- ── Restaurant Grid ── -->
<div class="page-heading">
    <h2 style="font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:700;">
        <i class="fa-solid fa-store" style="color:var(--primary);margin-right:.4rem;"></i>
        Open Restaurants
        <span style="font-size:.95rem;color:var(--text-muted);font-weight:400;margin-left:.5rem;">
            (<?= count($restaurants) ?> available)
        </span>
    </h2>
</div>

<?php if (empty($restaurants)): ?>
    <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted);">
        <i class="fa-solid fa-store-slash" style="font-size:3.5rem;margin-bottom:1rem;display:block;opacity:.4;"></i>
        <p style="font-size:1.1rem;">No restaurants are currently open. Check back soon!</p>
    </div>
<?php else: ?>
    <div class="grid-3" id="restaurant-grid">
        <?php foreach ($restaurants as $r): ?>
        <article class="card" data-name="<?= strtolower(htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8')) ?>"
                 style="cursor:default;">
            <?php if (!empty($r['image']) && file_exists($r['image'])): ?>
                <img src="<?= htmlspecialchars($r['image'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?>"
                     class="card-img-top">
            <?php else: ?>
                <div style="height:160px;background:linear-gradient(135deg,#ff6b35,#ffc947);
                            display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-utensils" style="font-size:3rem;color:#fff;opacity:.6;"></i>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h3 style="font-weight:700;margin-bottom:.4rem;">
                    <?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>
                <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:.8rem;min-height:2.5rem;">
                    <?= htmlspecialchars($r['description'] ?? 'Delicious food delivered to you.', ENT_QUOTES, 'UTF-8') ?>
                </p>
                <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <?php if (!empty($r['cuisine_type'])): ?>
                        <span class="badge badge-primary">
                            <i class="fa-solid fa-tag"></i>
                            <?= htmlspecialchars($r['cuisine_type'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($r['delivery_time'])): ?>
                        <span style="font-size:.82rem;color:var(--text-muted);">
                            <i class="fa-solid fa-clock" style="color:var(--primary);"></i>
                            <?= htmlspecialchars($r['delivery_time'], ENT_QUOTES, 'UTF-8') ?> min
                        </span>
                    <?php endif; ?>
                    <span class="badge badge-success">
                        <i class="fa-solid fa-circle" style="font-size:.45rem;"></i> Open
                    </span>
                </div>
                <a href="index.php?action=menu&restaurant_id=<?= (int)$r['id'] ?>"
                   class="btn btn-primary btn-block"
                   id="view-menu-<?= (int)$r['id'] ?>">
                    <i class="fa-solid fa-utensils"></i> View Menu
                </a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    function filterRestaurants(query) {
        const q    = query.toLowerCase().trim();
        const cards = document.querySelectorAll('#restaurant-grid .card');
        cards.forEach(card => {
            const name = card.dataset.name || '';
            card.style.display = name.includes(q) ? '' : 'none';
        });
    }
</script>

<?php require_once 'views/layouts/footer.php'; ?>
