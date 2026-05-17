<div class="mb-4">
    <h2><i class="fa-solid fa-store"></i> Restaurant Profile</h2>
    <p class="text-muted">Manage your restaurant details and settings.</p>
</div>

<div class="card" style="max-width: 800px;">
    <form method="POST" action="?route=manager/profile" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Restaurant Name</label>
            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($restaurant['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($restaurant['description'] ?? '') ?></textarea>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="cuisine_type">Cuisine Type</label>
                <input type="text" id="cuisine_type" name="cuisine_type" class="form-control" value="<?= htmlspecialchars($restaurant['cuisine_type'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="opening_hours">Opening Hours</label>
                <input type="text" id="opening_hours" name="opening_hours" class="form-control" placeholder="e.g. 10:00 AM - 10:00 PM" value="<?= htmlspecialchars($restaurant['opening_hours'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($restaurant['address'] ?? '') ?>">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($restaurant['city'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="delivery_radius_km">Delivery Radius (KM)</label>
                <input type="number" step="0.1" id="delivery_radius_km" name="delivery_radius_km" class="form-control" value="<?= htmlspecialchars($restaurant['delivery_radius_km'] ?? '5.0') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="logo">Restaurant Logo</label>
            <?php if(!empty($restaurant['logo_path'])): ?>
                <div class="mb-3">
                    <img src="<?= htmlspecialchars($restaurant['logo_path']) ?>" alt="Logo" style="max-width: 150px; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_open" value="1" <?= (isset($restaurant['is_open']) && $restaurant['is_open']) ? 'checked' : '' ?> style="width: 20px; height: 20px;">
                <span style="font-size: 1.1rem; font-weight: 500;">Restaurant is currently open for orders</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Profile</button>
    </form>
</div>
