<?php
// Guard: only redirect if user is not logged in AND we're NOT on an auth page
$public_actions = ['login', 'register'];
$current_action = $_GET['action'] ?? 'home';
if (!isset($_SESSION['user_id']) && !in_array($current_action, $public_actions)) {
    header('Location: index.php?action=login');
    exit();
}
$user_name = htmlspecialchars($_SESSION['user_name'] ?? 'Guest', ENT_QUOTES, 'UTF-8');
$user_pic  = htmlspecialchars($_SESSION['user_pic']  ?? '', ENT_QUOTES, 'UTF-8');
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FoodRush — Order delicious meals from top restaurants near you.">
    <title>FoodRush | Online Food Ordering</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:     #ff6b35;
            --primary-dk:  #e85520;
            --secondary:   #2d2d2d;
            --accent:      #ffc947;
            --bg:          #f8f9fa;
            --card-bg:     #ffffff;
            --text:        #2d2d2d;
            --text-muted:  #6c757d;
            --border:      #e9ecef;
            --success:     #28a745;
            --danger:      #dc3545;
            --info:        #17a2b8;
            --radius:      12px;
            --shadow:      0 4px 20px rgba(0,0,0,.08);
            --transition:  all .25s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .navbar-brand span { color: var(--secondary); }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: .5rem;
            list-style: none;
        }
        .navbar-nav a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: .45rem .8rem;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .navbar-nav a:hover, .navbar-nav a.active { color: var(--primary); background: #fff5f2; }
        .cart-badge {
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            font-size: .7rem;
            font-weight: 700;
            width: 18px; height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .navbar-user img {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .navbar-user span { font-weight: 600; font-size: .9rem; }

        /* ── Main container ── */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            flex: 1;
            width: 100%;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.2rem;
            border-radius: 8px;
            border: none;
            font-size: .92rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }
        .btn-primary   { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(255,107,53,.35); }
        .btn-secondary { background: var(--secondary); color: #fff; }
        .btn-secondary:hover { background: #444; }
        .btn-outline   { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline:hover { background: var(--primary); color: #fff; }
        .btn-danger    { background: var(--danger); color: #fff; }
        .btn-success   { background: var(--success); color: #fff; }
        .btn-sm { padding: .35rem .75rem; font-size: .82rem; }
        .btn-block { width: 100%; justify-content: center; }

        /* ── Cards ── */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        .card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.12); transform: translateY(-2px); }
        .card-body { padding: 1.5rem; }
        .card-img-top { width: 100%; height: 180px; object-fit: cover; }

        /* ── Alert ── */
        .alert {
            padding: .85rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .alert-danger  { background: #ffeaea; color: #c0392b; border-left: 4px solid var(--danger); }
        .alert-success { background: #eafaf1; color: #1e8449; border-left: 4px solid var(--success); }
        .alert-info    { background: #e8f8fb; color: #117a8b; border-left: 4px solid var(--info); }

        /* ── Form ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; font-weight: 600; margin-bottom: .4rem; font-size: .92rem; }
        .form-control {
            width: 100%;
            padding: .65rem 1rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: .95rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: #fafafa;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(255,107,53,.12); }

        /* ── Badge ── */
        .badge {
            display: inline-block;
            padding: .25rem .6rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-success  { background: #d4edda; color: #155724; }
        .badge-warning  { background: #fff3cd; color: #856404; }
        .badge-danger   { background: #f8d7da; color: #721c24; }
        .badge-info     { background: #d1ecf1; color: #0c5460; }
        .badge-primary  { background: #ffe5dc; color: var(--primary-dk); }

        /* ── Grid helpers ── */
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px,1fr)); gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 1.2rem; }

        /* ── Page heading ── */
        .page-heading { margin-bottom: 2rem; }
        .page-heading h1 { font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 800; }
        .page-heading p  { color: var(--text-muted); margin-top: .3rem; }

        @media (max-width: 768px) {
            .navbar-inner { padding: 0 1rem; }
            .main-content { padding: 1.2rem 1rem; }
            .navbar-nav a span { display: none; }
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-inner">
        <a href="index.php?action=dashboard" class="navbar-brand" id="nav-brand">
            <i class="fa-solid fa-bowl-food"></i>Food<span>Rush</span>
        </a>

        <ul class="navbar-nav">
            <li>
                <a href="index.php?action=dashboard" id="nav-home"
                   class="<?= $current_action === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i><span>Home</span>
                </a>
            </li>
            <li>
                <a href="index.php?action=order_history" id="nav-orders"
                   class="<?= $current_action === 'order_history' ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt"></i><span>My Orders</span>
                </a>
            </li>
            <li>
                <a href="index.php?action=checkout" id="nav-cart"
                   class="<?= $current_action === 'checkout' ? 'active' : '' ?>">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cart-badge-count" class="cart-badge"><?= $cart_count ?></span>
                </a>
            </li>
        </ul>

        <div class="navbar-user">
            <?php if ($user_pic && file_exists($user_pic)): ?>
                <img src="<?= $user_pic ?>" alt="Profile picture of <?= $user_name ?>">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'U') ?>&background=ff6b35&color=fff&size=40" alt="Avatar">
            <?php endif; ?>
            <span><?= $user_name ?></span>
            <a href="index.php?action=logout" class="btn btn-outline btn-sm" id="nav-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="main-content" id="main-content">
