<?php
$pageTitle = 'My Addresses | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.addr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
.addr-card { background: var(--card-bg); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); position: relative; border: 1px solid var(--border); }
.addr-card.is-default { border-color: var(--brand); box-shadow: 0 4px 12px rgba(255,107,53,0.15); }
.def-badge { position: absolute; top: 20px; right: 20px; background: var(--brand); color: white; font-size: 11px; padding: 4px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; }
.addr-label { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 10px; }
.addr-text { font-size: 14px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px; }
.addr-actions { display: flex; gap: 10px; }

.add-addr-form { background: #f8fafc; padding: 25px; border-radius: var(--radius); border: 1px dashed #ccc; margin-bottom: 30px; }
</style>

<h2 class="page-title"><i class="fa-solid fa-location-dot"></i> Saved Addresses</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="add-addr-form">
    <h3 style="margin-bottom:15px; font-size:16px;">+ Add New Address</h3>
    <form method="POST" action="index.php?action=addresses" style="display:flex; gap:15px; align-items:flex-end; flex-wrap:wrap;">
        <input type="hidden" name="addr_action" value="add">
        <div class="form-group" style="flex:1; min-width:200px; margin-bottom:0;">
            <label>Label (e.g. Home, Office)</label>
            <input type="text" name="label" class="form-control" required>
        </div>
        <div class="form-group" style="flex:2; min-width:250px; margin-bottom:0;">
            <label>Street Address</label>
            <input type="text" name="address_line" class="form-control" required>
        </div>
        <div class="form-group" style="flex:1; min-width:150px; margin-bottom:0;">
            <label>City</label>
            <input type="text" name="city" class="form-control" required>
        </div>
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
            <input type="checkbox" name="is_default" id="def" value="1">
            <label for="def" style="margin:0; font-size:13px; font-weight:600;">Set Default</label>
        </div>
        <button type="submit" class="btn" style="height: 42px;"><i class="fa-solid fa-plus"></i> Save</button>
    </form>
</div>

<?php if (empty($addresses)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-map-location-dot"></i>
        <p>You haven't saved any addresses yet.</p>
    </div>
<?php else: ?>
    <div class="addr-grid">
        <?php foreach ($addresses as $addr): ?>
            <div class="addr-card <?= $addr['is_default'] ? 'is-default' : '' ?>">
                <?php if ($addr['is_default']): ?>
                    <span class="def-badge">Default</span>
                <?php endif; ?>
                
                <div class="addr-label"><i class="fa-solid fa-thumbtack" style="color:#aaa;margin-right:6px;"></i> <?= htmlspecialchars($addr['label']) ?></div>
                <div class="addr-text">
                    <?= htmlspecialchars($addr['address_line']) ?><br>
                    <?= htmlspecialchars($addr['city']) ?>
                </div>
                
                <div class="addr-actions">
                    <?php if (!$addr['is_default']): ?>
                        <form method="POST" action="index.php?action=addresses" style="margin:0;">
                            <input type="hidden" name="addr_action" value="default">
                            <input type="hidden" name="addr_id" value="<?= $addr['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="padding:6px 12px; font-size:12px;">Set Default</button>
                        </form>
                    <?php endif; ?>
                    <form method="POST" action="index.php?action=addresses" style="margin:0;" onsubmit="return confirm('Delete this address?');">
                        <input type="hidden" name="addr_action" value="delete">
                        <input type="hidden" name="addr_id" value="<?= $addr['id'] ?>">
                        <button type="submit" class="btn btn-danger" style="padding:6px 12px; font-size:12px;"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
