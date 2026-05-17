<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fa-solid fa-list-ul"></i> Menu Management</h2>
        <p class="text-muted">Organize categories and items.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="openModal('categoryModal')"><i class="fa-solid fa-folder-plus"></i> Add Category</button>
        <button class="btn btn-primary" onclick="openModal('itemModal')"><i class="fa-solid fa-plus"></i> Add Item</button>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($categories)): ?>
                        <tr><td colspan="3" class="text-muted text-center">No categories found.</td></tr>
                    <?php else: foreach($categories as $cat): ?>
                        <tr>
                            <td><?= $cat['display_order'] ?></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td class="text-right">
                                <form method="POST" action="?route=manager/menu" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_category">
                                    <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?');"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Menu Items</h3>
        </div>
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="4" class="text-muted text-center">No items found.</td></tr>
                    <?php else: foreach($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($item['category_name']) ?></small>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <?php if($item['is_available']): ?>
                                    <span class="badge badge-ready">Available</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Sold Out</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <form method="POST" action="?route=manager/menu" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_item">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete item?');"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="categoryModal" class="modal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal('categoryModal')">&times;</button>
        <h3 class="mb-4">Add Menu Category</h3>
        <form method="POST" action="?route=manager/menu">
            <input type="hidden" name="action" value="add_category">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" class="form-control" value="0">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Category</button>
        </form>
    </div>
</div>

<div id="itemModal" class="modal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal('itemModal')">&times;</button>
        <h3 class="mb-4">Add Menu Item</h3>
        <form method="POST" action="?route=manager/menu" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_item">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" id="itemImageInput" class="form-control" accept="image/*">
                    <div id="imagePreviewContainer" style="display: none; margin-top: 12px; text-align: center;">
                        <img id="itemImagePreview" src="" alt="Image Preview" style="max-width: 120px; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_available" value="1" checked style="width: 18px; height: 18px;">
                    Item is currently available
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Item</button>
        </form>
    </div>
</div>

<script>
    const itemImageInput = document.getElementById('itemImageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const itemImagePreview = document.getElementById('itemImagePreview');

    if (itemImageInput) {
        itemImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    itemImagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                itemImagePreview.src = '';
                imagePreviewContainer.style.display = 'none';
            }
        });
    }
</script>
