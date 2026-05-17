<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Food Ordering System</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
  .card { background: #fff; padding: 35px 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 400px; }
  h2 { text-align: center; margin-bottom: 8px; color: #ff6b35; }
  .subtitle { text-align: center; color: #777; font-size: 14px; margin-bottom: 24px; }
  .form-group { margin-bottom: 14px; }
  .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
  .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
  .form-group input:focus { border-color: #ff6b35; outline: none; }
  .btn { width: 100%; padding: 11px; background: #ff6b35; color: white; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; margin-top: 6px; }
  .btn:hover { background: #e55a25; }
  .link-row { text-align: center; margin-top: 16px; font-size: 13px; color: #777; }
  .link-row a { color: #ff6b35; text-decoration: none; }
  .error { background: #fde8e8; color: #c0392b; padding: 9px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; }
</style>
</head>
<body>
<div class="card">
  <h2>🍔 FoodOrder</h2>
  <p class="subtitle">Create a new account</p>

  <?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?action=register" enctype="multipart/form-data">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="name" required placeholder="John Doe">
    </div>
    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" required placeholder="you@example.com">
    </div>
    <div class="form-group">
      <label>Phone Number</label>
      <input type="text" name="phone" required placeholder="+1 555 0100">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required placeholder="Min 6 characters">
    </div>
    <div class="form-group">
      <label>Profile Picture <small style="color:#aaa">(optional)</small></label>
      <input type="file" name="profile_pic" accept="image/*">
    </div>
    <button type="submit" class="btn">Create Account</button>
  </form>
  <div class="link-row">Already have an account? <a href="index.php?action=login">Login</a></div>
</div>
</body>
</html>
