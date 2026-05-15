<?php
// Prevent logged-in users from seeing login page
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?action=dashboard');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sign in to FoodRush and order delicious food online.">
    <title>FoodRush | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary:#ff6b35; --primary-dk:#e85520;
            --bg:#f8f9fa; --card:#fff;
            --text:#2d2d2d; --muted:#6c757d;
            --border:#e9ecef; --radius:16px;
        }
        body {
            font-family:'Inter',sans-serif;
            background: linear-gradient(135deg,#fff5f2 0%,#fff8f0 50%,#f0f9ff 100%);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:1rem;
        }
        .auth-wrapper {
            display:grid;
            grid-template-columns:1fr 1fr;
            min-height:580px;
            width:100%;
            max-width:900px;
            border-radius:var(--radius);
            overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,.12);
        }
        .auth-hero {
            background: linear-gradient(160deg,#ff6b35,#ff8c60,#ffc947);
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            padding:3rem 2rem;
            color:#fff;
            text-align:center;
        }
        .auth-hero .hero-icon { font-size:5rem; margin-bottom:1.5rem; opacity:.95; }
        .auth-hero h2 { font-family:'Poppins',sans-serif; font-size:2rem; font-weight:800; margin-bottom:.7rem; }
        .auth-hero p  { opacity:.88; font-size:1rem; line-height:1.6; }
        .auth-hero .tagline { margin-top:2rem; font-size:.85rem; opacity:.7; }

        .auth-form-box {
            background:#fff;
            padding:3rem 2.5rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .auth-title { font-family:'Poppins',sans-serif; font-size:1.7rem; font-weight:800; color:var(--text); margin-bottom:.3rem; }
        .auth-subtitle { color:var(--muted); font-size:.93rem; margin-bottom:2rem; }

        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block; font-weight:600; margin-bottom:.4rem; font-size:.9rem; color:var(--text); }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:var(--muted); font-size:.95rem; }
        .form-control {
            width:100%;
            padding:.7rem 1rem .7rem 2.6rem;
            border:2px solid var(--border);
            border-radius:10px;
            font-size:.95rem;
            font-family:'Inter',sans-serif;
            transition:all .25s;
            background:#fafafa;
        }
        .form-control:focus { outline:none; border-color:var(--primary); background:#fff; box-shadow:0 0 0 3px rgba(255,107,53,.12); }

        .btn {
            display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
            width:100%; padding:.8rem 1.5rem;
            border:none; border-radius:10px;
            font-size:1rem; font-weight:700; cursor:pointer;
            transition:all .25s; font-family:'Inter',sans-serif;
            text-decoration:none;
        }
        .btn-primary { background:var(--primary); color:#fff; }
        .btn-primary:hover { background:var(--primary-dk); transform:translateY(-1px); box-shadow:0 6px 18px rgba(255,107,53,.35); }

        .alert {
            padding:.8rem 1.1rem; border-radius:8px; margin-bottom:1.2rem;
            font-weight:500; display:flex; align-items:center; gap:.5rem; font-size:.9rem;
        }
        .alert-danger  { background:#ffeaea; color:#c0392b; border-left:4px solid #dc3545; }
        .alert-success { background:#eafaf1; color:#1e8449; border-left:4px solid #28a745; }

        .auth-footer { text-align:center; margin-top:1.5rem; font-size:.9rem; color:var(--muted); }
        .auth-footer a { color:var(--primary); font-weight:600; text-decoration:none; }
        .auth-footer a:hover { text-decoration:underline; }

        @media(max-width:640px) {
            .auth-wrapper { grid-template-columns:1fr; min-height:auto; }
            .auth-hero { display:none; }
            .auth-form-box { padding:2rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <!-- Hero Panel -->
    <div class="auth-hero">
        <div class="hero-icon"><i class="fa-solid fa-bowl-food"></i></div>
        <h2>Welcome Back!</h2>
        <p>Sign in and explore hundreds of restaurants ready to deliver right to your door.</p>
        <p class="tagline">🍕 🍔 🍜 🌮 🍣</p>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-box">
        <h1 class="auth-title">Sign In</h1>
        <p class="auth-subtitle">Enter your credentials to continue</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login" id="login-form" novalidate>
            <div class="form-group">
                <label class="form-label" for="login-email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input
                        type="email"
                        id="login-email"
                        name="email"
                        class="form-control"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="login-password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input
                        type="password"
                        id="login-password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="login-btn">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="index.php?action=register" id="go-register">Create one</a>
        </div>
    </div>
</div>

</body>
</html>
