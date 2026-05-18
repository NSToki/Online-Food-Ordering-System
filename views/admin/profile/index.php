<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">

	<h1>My Profile</h1>

	<p>
		Your administrator account details
	</p>

</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">

	<div class="card">

		<div class="card-body" style="text-align:center;padding:36px 24px;">

			<?php
				if (!empty($admin['profile_pic'])) {
			?>

					<img
						src="data:image/jpeg;base64,<?php echo base64_encode($admin['profile_pic']); ?>"
						alt="Profile Picture"
						style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid var(--primary);box-shadow:var(--shadow-primary);">

			<?php
				} else {
			?>

					<img
						src="https://ui-avatars.com/api/?name=<?php echo urlencode(htmlspecialchars($admin['name'])); ?>&background=E8621A&color=fff&bold=true&size=120"
						alt="<?php echo htmlspecialchars($admin['name']); ?>"
						style="width:120px;height:120px;border-radius:50%;border:4px solid var(--primary);box-shadow:var(--shadow-primary);">

			<?php
				}
			?>

			<h2 style="margin-top:18px;font-size:20px;font-weight:700;">

				<?php
					echo htmlspecialchars($admin['name']);
				?>

			</h2>

			<span class="badge-status badge-success" style="margin-top:6px;">
				Platform Admin
			</span>

		</div>

	</div>

	<div class="card">

		<div class="card-header">

			<div>

				<h2>
					Account Information
				</h2>

				<p>
					Your registered details on FoodPathai
				</p>

			</div>

		</div>

		<div class="card-body">

			<div style="display:grid;gap:20px;">

				<div style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--bg);border-radius:var(--radius-sm);">

					<div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--primary-glow);display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:17px;flex-shrink:0;">

						<i class="fa-solid fa-user"></i>

					</div>

					<div>

						<div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">
							Full Name
						</div>

						<div style="font-size:15px;font-weight:600;margin-top:2px;">

							<?php
								echo htmlspecialchars($admin['name']);
							?>

						</div>

					</div>

				</div>

				<div style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--bg);border-radius:var(--radius-sm);">

					<div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--info-bg);display:flex;align-items:center;justify-content:center;color:var(--info);font-size:17px;flex-shrink:0;">

						<i class="fa-regular fa-envelope"></i>

					</div>

					<div>

						<div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">
							Email Address
						</div>

						<div style="font-size:15px;font-weight:600;margin-top:2px;">

							<?php
								echo htmlspecialchars($admin['email']);
							?>

						</div>

					</div>

				</div>

				<div style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--bg);border-radius:var(--radius-sm);">

					<div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--success-bg);display:flex;align-items:center;justify-content:center;color:var(--success);font-size:17px;flex-shrink:0;">

						<i class="fa-solid fa-phone"></i>

					</div>

					<div>

						<div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">
							Phone
						</div>

						<div style="font-size:15px;font-weight:600;margin-top:2px;">

							<?php
								echo htmlspecialchars($admin['phone'] ?? 'Not provided');
							?>

						</div>

					</div>

				</div>

				<div style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--bg);border-radius:var(--radius-sm);">

					<div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--warning-bg);display:flex;align-items:center;justify-content:center;color:var(--warning);font-size:17px;flex-shrink:0;">

						<i class="fa-solid fa-calendar-days"></i>

					</div>

					<div>

						<div style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.6px;">
							Member Since
						</div>

						<div style="font-size:15px;font-weight:600;margin-top:2px;">

							<?php
								echo htmlspecialchars(date('F j, Y', strtotime($admin['created_at'])));
							?>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>