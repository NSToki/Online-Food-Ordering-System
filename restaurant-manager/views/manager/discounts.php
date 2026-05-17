<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fa-solid fa-tags"></i> Discounts & Promotions</h2>
        <p class="text-muted">Manage limited-time offers for your menu items.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('discountModal')"><i class="fa-solid fa-plus"></i> New Discount</button>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Discount</th>
                    <th>Validity Period</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($discounts)): ?>
                    <tr><td colspan="5" class="text-muted text-center">No active discounts.</td></tr>
                <?php else: foreach($discounts as $d): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($d['item_name']) ?></strong><br>
                            <small class="text-muted">Original: $<?= number_format($d['original_price'], 2) ?></small>
                        </td>
                        <td>
                            <span style="font-size: 1.1rem; font-weight: 600; color: var(--secondary);"><?= number_format($d['discount_pct'], 0) ?>% OFF</span>
                        </td>
                        <td>
                            <?= htmlspecialchars($d['valid_from']) ?> <br>to <?= htmlspecialchars($d['valid_until']) ?>
                        </td>
                        <td>
                            <?php if($d['is_active']): ?>
                                <span class="badge badge-ready">Active</span>
                            <?php else: ?>
                                <span class="badge badge-cancelled">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <form method="POST" action="?route=manager/discounts" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="discount_id" value="<?= $d['id'] ?>">
                                <input type="hidden" name="is_active" value="<?= $d['is_active'] ? '0' : '1' ?>">
                                <button type="submit" class="btn <?= $d['is_active'] ? 'btn-secondary' : 'btn-primary' ?> btn-sm">
                                    <?= $d['is_active'] ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                            <form method="POST" action="?route=manager/discounts" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="discount_id" value="<?= $d['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete discount?');"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Discount Modal -->
<div id="discountModal" class="modal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal('discountModal')">&times;</button>
        <h3 class="mb-4">Create Discount Offer</h3>
        <form method="POST" action="?route=manager/discounts">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Menu Item</label>
                <select name="menu_item_id" class="form-control" required>
                    <?php foreach($items as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?> ($<?= number_format($item['price'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Discount Percentage (%)</label>
                <input type="number" step="0.1" name="discount_pct" class="form-control" required min="1" max="100">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Valid From</label>
                    <input type="date" name="valid_from" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Valid Until</label>
                    <input type="date" name="valid_until" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                    Activate immediately
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Discount</button>
        </form>
    </div>
</div>
