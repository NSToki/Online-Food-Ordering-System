<?php require_once 'views/layouts/header.php'; ?>

<!-- ── Restaurant Header ── -->
<div style="display:flex;align-items:center;gap:.8rem;margin-bottom:1.5rem;">
    <a href="index.php?action=dashboard" class="btn btn-outline btn-sm" id="back-to-home">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div>
        <h1 style="font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:800;margin:0;">
            <?= htmlspecialchars($restaurant['name'], ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <p style="color:var(--text-muted);margin:0;font-size:.9rem;">
            <?= htmlspecialchars($restaurant['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>
</div>

<!-- ── Flash / Cart Notice ── -->
<?php if (!empty($_SESSION['cart'])): ?>
<div class="alert alert-info" style="justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
    <span>
        <i class="fa-solid fa-cart-shopping"></i>
        You have <strong id="notice-count"><?= array_sum(array_column($_SESSION['cart'], 'quantity')) ?></strong> item(s) in your cart.
    </span>
    <a href="index.php?action=checkout" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-credit-card"></i> Checkout
    </a>
</div>
<?php endif; ?>

<!-- ── Category Filter Tabs ── -->
<?php if (!empty($categories)): ?>
<div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;" id="category-tabs">
    <button class="btn btn-primary btn-sm category-tab active" data-cat="all" onclick="filterCategory('all', this)">
        All
    </button>
    <?php foreach ($categories as $cat): ?>
    <button class="btn btn-outline btn-sm category-tab"
            data-cat="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>"
            onclick="filterCategory(<?= json_encode($cat) ?>, this)">
        <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
    </button>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Menu Items Grid ── -->
<?php if (empty($menu_items)): ?>
    <div style="text-align:center;padding:4rem;color:var(--text-muted);">
        <i class="fa-solid fa-plate-wheat" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
        <p>No menu items are currently available.</p>
    </div>
<?php else: ?>
<div class="grid-3" id="menu-grid">
    <?php foreach ($menu_items as $item): ?>
    <article class="card menu-item-card"
             data-cat="<?= htmlspecialchars($item['category'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8') ?>">
        <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
            <img src="<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>"
                 class="card-img-top" style="height:150px;">
        <?php else: ?>
            <div style="height:130px;background:linear-gradient(135deg,#2d2d2d,#555,#ff6b35);
                        display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-drumstick-bite" style="font-size:2.5rem;color:#fff;opacity:.5;"></i>
            </div>
        <?php endif; ?>
        <div class="card-body" style="padding:1.2rem;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.4rem;">
                <h3 style="font-size:1rem;font-weight:700;flex:1;">
                    <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>
                <strong style="color:var(--primary);font-size:1rem;white-space:nowrap;margin-left:.5rem;">
                    $<?= number_format($item['price'], 2) ?>
                </strong>
            </div>
            <?php if (!empty($item['description'])): ?>
                <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:.8rem;min-height:2.2rem;line-height:1.5;">
                    <?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($item['category'])): ?>
                <span class="badge badge-info" style="margin-bottom:.8rem;font-size:.72rem;">
                    <?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            <?php endif; ?>
            <button
                class="btn btn-primary btn-block btn-sm add-to-cart-btn"
                id="add-<?= (int)$item['id'] ?>"
                onclick="addToCart(<?= (int)$item['id'] ?>, <?= json_encode($item['name']) ?>, <?= (float)$item['price'] ?>, this)"
            >
                <i class="fa-solid fa-plus"></i> Add to Cart
            </button>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Floating Cart Summary ── -->
<?php if (!empty($_SESSION['cart'])): ?>
<div style="
    position:fixed; bottom:1.5rem; right:1.5rem;
    background:var(--primary); color:#fff;
    border-radius:50px; padding:.8rem 1.4rem;
    box-shadow:0 8px 28px rgba(255,107,53,.45);
    display:flex; align-items:center; gap:.8rem;
    cursor:pointer; z-index:500; font-weight:700;"
    onclick="window.location='index.php?action=checkout'"
    id="floating-cart"
>
    <i class="fa-solid fa-cart-shopping"></i>
    <span id="floating-cart-text">
        <?= array_sum(array_column($_SESSION['cart'], 'quantity')) ?> item(s) &mdash;
        $<?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $_SESSION['cart'])), 2) ?>
    </span>
    <i class="fa-solid fa-arrow-right"></i>
</div>
<?php endif; ?>

<script>
    // Category filter
    function filterCategory(cat, btn) {
        document.querySelectorAll('.category-tab').forEach(t => {
            t.classList.remove('active');
            t.style.background   = '';
            t.style.color        = '';
            t.style.borderColor  = '';
        });
        btn.classList.add('active');

        document.querySelectorAll('.menu-item-card').forEach(card => {
            if (cat === 'all' || card.dataset.cat === cat) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Add to cart via AJAX
    async function addToCart(id, name, price, btn) {
        const original = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding…';

        const payload = {
            action:     'add_cart',
            item_id:    id,
            item_name:  name,
            item_price: price
        };

        try {
            const res = await ajaxPost(payload);
            if (res.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Added!';
                btn.style.background = '#28a745';
                updateCartBadge(res.total_items);

                // Update notice count if visible
                const notice = document.getElementById('notice-count');
                if (notice) notice.textContent = res.total_items;

                // Update floating cart
                const fc = document.getElementById('floating-cart');
                if (fc) {
                    document.getElementById('floating-cart-text').textContent =
                        res.total_items + ' item(s) — $' + res.cart_total;
                    fc.style.display = 'flex';
                }

                setTimeout(() => {
                    btn.innerHTML    = original;
                    btn.style.background = '';
                    btn.disabled     = false;
                }, 1800);
            } else {
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Error';
                setTimeout(() => { btn.innerHTML = original; btn.disabled = false; }, 2000);
            }
        } catch (e) {
            btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Failed';
            setTimeout(() => { btn.innerHTML = original; btn.disabled = false; }, 2000);
        }
    }
</script>

<?php require_once 'views/layouts/footer.php'; ?>
