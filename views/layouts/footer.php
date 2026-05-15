</main><!-- /.main-content -->

<footer style="background:#2d2d2d; color:#adb5bd; text-align:center; padding:1.5rem 1rem; margin-top:auto; font-size:.88rem;">
    <p>
        &copy; <?= date('Y') ?>
        <strong style="color:#ff6b35;">FoodRush</strong> &mdash;
        Online Food Ordering System &nbsp;|&nbsp;
        Built with <i class="fa-solid fa-heart" style="color:#ff6b35;"></i> &amp; PHP
    </p>
</footer>

<!-- Global JS helpers -->
<script>
    // Update cart badge count globally
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge-count');
        if (badge) {
            badge.textContent = count;
            badge.style.transform = 'scale(1.3)';
            setTimeout(() => badge.style.transform = 'scale(1)', 300);
        }
    }

    // Generic fetch AJAX helper
    async function ajaxPost(data) {
        const form = new URLSearchParams(data);
        const res  = await fetch('index.php?action=ajax_add_cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: form.toString()
        });
        return res.json();
    }
</script>
</body>
</html>
