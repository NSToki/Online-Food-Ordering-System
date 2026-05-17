<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'FoodOrder') ?></title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
  :root {
    --brand: #e67e22;         /* Flat Orange theme color */
    --brand-dark: #d35400;    /* Dark Orange hover color */
    --bg: #f9f9f9;            /* Standard clean off-white background */
    --card-bg: #ffffff;
    --text: #333333;
    --text-muted: #666666;
    --border: #cccccc;        /* Normal plain border colour */
    --radius: 4px;            /* Very slight basic radius */
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, Helvetica, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }
  
  /* Simple Classic Navbar */
  .navbar { background: var(--brand); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid var(--brand-dark); position: sticky; top: 0; z-index: 100; }
  .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 8px; }
  .nav-links { display: flex; align-items: center; gap: 20px; }
  .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: normal; display: flex; align-items: center; gap: 5px; }
  .nav-links a:hover { text-decoration: underline; }
  
  /* Main Container */
  .main-content { flex: 1; max-width: 1000px; width: 100%; margin: 25px auto; padding: 0 20px; }
  
  /* Typography & Basics */
  h2.page-title { margin-bottom: 20px; color: var(--brand); font-size: 22px; font-weight: bold; border-bottom: 2px solid var(--brand); padding-bottom: 10px; }
  a { text-decoration: none; color: var(--brand-dark); }
  a:hover { text-decoration: underline; }
  
  /* Standard Simple UI Elements */
  .card { background: white; border: 1px solid #cccccc; border-radius: var(--radius); padding: 20px; margin-bottom: 20px; }
  .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 16px; background: var(--brand); color: white; border: 1px solid var(--brand-dark); border-radius: var(--radius); font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; }
  .btn:hover { background: var(--brand-dark); }
  .btn-outline { background: white; color: var(--brand); border: 1px solid var(--brand); }
  .btn-outline:hover { background: #f0f0f0; }
  .btn-danger { background: #c0392b; border-color: #962d22; }
  .btn-danger:hover { background: #962d22; }
  
  .alert { padding: 10px 15px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px; border: 1px solid #ccc; }
  .alert-success { background: #d4edda; color: #155724; border-color: #c3e6cb; }
  .alert-danger { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
  
  /* Normal Forms */
  .form-group { margin-bottom: 15px; }
  .form-group label { display: block; font-size: 14px; font-weight: bold; color: #333; margin-bottom: 5px; }
  .form-control { width: 100%; padding: 8px 12px; border: 1px solid #cccccc; border-radius: var(--radius); font-size: 14px; font-family: inherit; }
  .form-control:focus { border-color: var(--brand); outline: none; }
  
  /* Empty States */
  .empty-state { text-align: center; padding: 40px 20px; color: #666; border: 1px dashed #ccc; background: white; }
  .empty-state i { font-size: 40px; color: #999; margin-bottom: 15px; }
  .empty-state p { font-size: 15px; margin-bottom: 15px; }
  
  /* Robust Dropdown Layout */
  .dropdown-wrapper { position: relative; display: inline-block; margin-left: 15px; }
  .dropdown-trigger { background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 20px; color: white !important; font-size: 14px; text-decoration: none; cursor: pointer; display: flex; align-items: center; gap: 5px; }
  .dropdown-menu { display: none; position: absolute; right: 0; top: 100%; background: white; min-width: 180px; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; z-index: 1000; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
  .dropdown-menu a { display: block !important; color: #333 !important; padding: 10px 15px !important; border-bottom: 1px solid #eee; text-decoration: none !important; font-size: 13px !important; font-weight: normal !important; text-align: left; }
  .dropdown-menu a:hover { background: #f5f5f5 !important; color: var(--brand) !important; text-decoration: none !important; }
  .dropdown-menu a:last-child { border-bottom: none; }
</style>
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php?action=dashboard"><i class="fa-solid fa-burger"></i> FoodOrder</a>
  <div class="nav-links">
    <a href="index.php?action=dashboard"><i class="fa-solid fa-store"></i> Restaurants</a>
    <a href="index.php?action=cart">
        <i class="fa-solid fa-cart-shopping"></i> Cart (<span id="nav-cart-count"><?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')) ?></span>)
    </a>
    
    <div class="dropdown-wrapper">
      <a href="index.php?action=profile" class="dropdown-trigger">
        <i class="fa-solid fa-user-circle"></i> <?= htmlspecialchars($_SESSION['name'] ?? 'Account') ?>
      </a>
      <div class="dropdown-menu">
        <a href="index.php?action=profile"><i class="fa-solid fa-id-card"></i> My Profile</a>
        <a href="index.php?action=addresses"><i class="fa-solid fa-location-dot"></i> Addresses</a>
        <a href="index.php?action=favourites"><i class="fa-solid fa-heart"></i> Favourites</a>
        <a href="index.php?action=order_history"><i class="fa-solid fa-receipt"></i> Orders</a>
        <a href="index.php?action=reviews"><i class="fa-solid fa-star"></i> Reviews</a>
        <a href="index.php?action=complaints"><i class="fa-solid fa-headset"></i> Complaints</a>
        <a href="index.php?action=logout" style="color:#dc3545 !important;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </div>
  </div>
</nav>

<script>
  // Robust click-to-toggle dropdown menu
  const trigger = document.querySelector('.dropdown-trigger');
  const menu = document.querySelector('.dropdown-menu');

  if (trigger && menu) {
      trigger.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          const isOpen = menu.style.display === 'block';
          menu.style.display = isOpen ? 'none' : 'block';
      });

      // Close menu if clicking anywhere else on page
      document.addEventListener('click', function() {
          menu.style.display = 'none';
      });

      // Prevent clicks inside the menu itself from closing it
      menu.addEventListener('click', function(e) {
          e.stopPropagation();
      });
  }
</script>

<div class="main-content">
