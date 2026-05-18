<?php
$pageTitle = 'My Profile | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.profile-wrap { display: flex; gap: 30px; align-items: flex-start; }
@media (max-width: 768px) { .profile-wrap { flex-direction: column; } }
.profile-card { flex: 1; background: var(--card-bg); border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow); }
.pic-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #eee; margin-bottom: 20px; background:#ddd; }
h3 { margin-bottom: 20px; font-size: 18px; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 10px; }
</style>

<h2 class="page-title"><i class="fa-solid fa-id-card"></i> My Profile</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><i class="fa-solid fa-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="profile-wrap">
    
    <!-- Update Personal Info -->
    <div class="profile-card">
        <h3>Personal Information</h3>
        <form method="POST" action="index.php?action=profile" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="profile">
            
            <div style="text-align: center;">
                <img src="<?= $user['profile_pic'] ? 'assets/uploads/'.$user['profile_pic'] : 'https://placehold.co/120x120?text=User' ?>" class="pic-preview">
            </div>
            
            <div class="form-group">
                <label>Update Profile Picture</label>
                <input type="file" name="profile_pic" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div class="form-group">
                <label>Email Address <small>(Cannot be changed)</small></label>
                <input type="email" class="form-control" disabled value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($user['phone']) ?>">
            </div>
            
            <button type="submit" class="btn" style="width:100%; margin-top:10px;"><i class="fa-solid fa-floppy-disk"></i> Save Profile</button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="profile-card">
        <h3>Change Password</h3>
        <form method="POST" action="index.php?action=profile">
            <input type="hidden" name="form_type" value="password">
            
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="old_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password <small>(Min 6 chars)</small></label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn" style="width:100%; margin-top:10px;"><i class="fa-solid fa-key"></i> Update Password</button>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
