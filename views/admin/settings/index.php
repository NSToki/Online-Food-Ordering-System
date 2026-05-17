<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
	<h1>Platform Settings</h1>
	<p>Configure global financial and operational settings for the platform</p>
</div>

<?php
	if (!empty($message)) {
?>
		<div class="alert alert-success">
			<i class="fa-solid fa-circle-check"></i>
			<?php echo htmlspecialchars($message); ?>
		</div>
<?php
	}
?>

<?php
	if (!empty($error)) {
?>
		<div class="alert alert-danger">
			<i class="fa-solid fa-circle-exclamation"></i>
			<?php echo htmlspecialchars($error); ?>
		</div>
<?php
	}
?>

<form method="POST">

	<div class="card" style="margin-bottom:24px;">

		<div class="card-header">
			<div>
				<h2>
					<i class="fa-solid fa-dollar-sign" style="color:var(--primary);margin-right:8px;"></i>
					Financial Settings
				</h2>

				<p>
					Commission and delivery fee configuration
				</p>
			</div>
		</div>

		<div class="card-body">

			<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">

				<div class="form-group" style="margin-bottom:0;">

					<label class="form-label" for="commission_rate_pct">
						<i class="fa-solid fa-percent" style="color:var(--primary);margin-right:6px;"></i>
						Commission Rate (%)
					</label>

					<input
						type="number"
						step="0.01"
						min="0"
						max="100"
						id="commission_rate_pct"
						name="commission_rate_pct"
						class="form-control"
						value="<?php echo htmlspecialchars($settings['commission_rate_pct'] ?? 0); ?>"
						placeholder="e.g. 10.00">

				</div>

				<div class="form-group" style="margin-bottom:0;">

					<label class="form-label" for="base_delivery_fee">
						<i class="fa-solid fa-truck-fast" style="color:var(--primary);margin-right:6px;"></i>
						Base Delivery Fee ($)
					</label>

					<input
						type="number"
						step="0.01"
						min="0"
						id="base_delivery_fee"
						name="base_delivery_fee"
						class="form-control"
						value="<?php echo htmlspecialchars($settings['base_delivery_fee'] ?? 0); ?>"
						placeholder="e.g. 2.50">

				</div>

				<div class="form-group" style="margin-bottom:0;">

					<label class="form-label" for="delivery_fee_per_km">
						<i class="fa-solid fa-road" style="color:var(--primary);margin-right:6px;"></i>
						Delivery Fee per KM ($)
					</label>

					<input
						type="number"
						step="0.01"
						min="0"
						id="delivery_fee_per_km"
						name="delivery_fee_per_km"
						class="form-control"
						value="<?php echo htmlspecialchars($settings['delivery_fee_per_km'] ?? 0); ?>"
						placeholder="e.g. 0.50">

				</div>

			</div>

		</div>

	</div>

	<div class="card" style="margin-bottom:24px;">

		<div class="card-header">

			<div>

				<h2>
					<i class="fa-solid fa-utensils" style="color:var(--primary);margin-right:8px;"></i>
					Cuisine Categories
				</h2>

				<p>
					Enter one category per line
				</p>

			</div>

		</div>

		<div class="card-body">

			<div class="form-group" style="margin-bottom:0;">

				<label class="form-label" for="cuisine_categories">
					Categories
				</label>

				<textarea
					id="cuisine_categories"
					name="cuisine_categories"
					rows="7"
					class="form-control"
					placeholder="e.g.&#10;Burger&#10;Pizza&#10;Sushi&#10;Salad"><?php echo htmlspecialchars(trim($categoriesStr ?? '')); ?></textarea>

			</div>

		</div>

	</div>

	<div style="display:flex;justify-content:flex-end;">

		<button type="submit" class="btn btn-primary">
			<i class="fa-solid fa-floppy-disk"></i>
			Save Settings
		</button>

	</div>

</form>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>