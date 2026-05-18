<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | FoodOrder</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:Arial,sans-serif;background:#f0f2f5;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px;}
  .card{background:#fff;padding:36px 40px;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.12);width:400px;}
  h2{text-align:center;margin-bottom:6px;color:#ff6b35;font-size:22px;}
  .sub{text-align:center;color:#888;font-size:13px;margin-bottom:22px;}
  .fg{margin-bottom:13px;}
  .fg label{display:block;font-size:12px;font-weight:700;color:#555;margin-bottom:5px;text-transform:uppercase;letter-spacing:.04em;}
  .fg input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:7px;font-size:14px;outline:none;transition:border .2s;}
  .fg input:focus{border-color:#ff6b35;}
  .btn{width:100%;padding:11px;background:#ff6b35;color:#fff;border:none;border-radius:7px;font-size:15px;font-weight:700;cursor:pointer;margin-top:4px;transition:background .2s;}
  .btn:hover{background:#e55a25;}
  .link{text-align:center;margin-top:16px;font-size:13px;color:#888;}
  .link a{color:#ff6b35;text-decoration:none;}
  .alert-err{background:#fde8e8;color:#c0392b;padding:9px 12px;border-radius:7px;font-size:13px;margin-bottom:14px;}
</style>
</head>
<body>
<div class="card">
  <h2> FoodOrder</h2>
  <p class="sub">Create a new customer account</p>

  <?php if (!empty($error)): ?>
    <div class="alert-err"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?action=register" enctype="multipart/form-data">
    <div class="fg">
      <label>Full Name</label>
      <input type="text" name="name" required placeholder="John Doe"
             value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div class="fg">
      <label>Email Address</label>
      <input type="email" name="email" required placeholder="you@example.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="fg">
      <label>Phone Number</label>
      <input type="text" name="phone" required placeholder="+1 555 0100"
             value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    </div>
    <div class="fg">
      <label>Password <small style="color:#aaa;font-weight:400">(min 6 chars)</small></label>
      <input type="password" name="password" required placeholder="••••••••">
    </div>
    <div class="fg">
      <label>Profile Picture <small style="color:#aaa;font-weight:400">(optional)</small></label>
      <input type="file" name="profile_pic" accept="image/*">
    </div>
    <button type="submit" class="btn">Create Account</button>
  </form>
  <div class="link">Already have an account? <a href="index.php?action=login">Login</a></div>
</div>
</body>
</html>
