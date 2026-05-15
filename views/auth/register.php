<?php
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
    <meta name="description" content="Create your FoodRush account and start ordering food online.">
    <title>FoodRush | Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --primary:#ff6b35; --primary-dk:#e85520;
            --bg:#f8f9fa; --card:#fff;
            --text:#2d2d2d; --muted:#6c757d;
            --border:#e9ecef; --radius:16px;
        }
        body {
            font-family:'Inter',sans-serif;
            background:linear-gradient(135deg,#fff5f2 0%,#fff8f0 50%,#f0f9ff 100%);
            min-height:100vh;
            display:flex; align-items:center; justify-content:center; padding:2rem 1rem;
        }
        .auth-wrapper {
            display:grid; grid-template-columns:1fr 1fr;
            width:100%; max-width:960px;
            border-radius:var(--radius); overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,.12);
        }
        .auth-hero {
            background:linear-gradient(160deg,#2d2d2d,#4a4a4a,#ff6b35);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding:3rem 2rem; color:#fff; text-align:center;
        }
        .auth-hero .hero-icon { font-size:4.5rem; margin-bottom:1.5rem; }
        .auth-hero h2 { font-family:'Poppins',sans-serif; font-size:1.9rem; font-weight:800; margin-bottom:.7rem; }
        .auth-hero p  { opacity:.85; font-size:.97rem; line-height:1.7; }
        .auth-hero ul { list-style:none; margin-top:1.5rem; text-align:left; }
        .auth-hero ul li { padding:.35rem 0; opacity:.85; font-size:.9rem; }
        .auth-hero ul li i { color:var(--primary); margin-right:.5rem; }

        .auth-form-box {
            background:#fff; padding:2.5rem;
            display:flex; flex-direction:column; justify-content:center; overflow-y:auto;
        }
        .auth-title    { font-family:'Poppins',sans-serif; font-size:1.6rem; font-weight:800; color:var(--text); margin-bottom:.3rem; }
        .auth-subtitle { color:var(--muted); font-size:.9rem; margin-bottom:1.5rem; }

        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-group { margin-bottom:1rem; }
        .form-label { display:block; font-weight:600; margin-bottom:.35rem; font-size:.88rem; color:var(--text); }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:var(--muted); font-size:.9rem; }
        .form-control {
            width:100%; padding:.65rem 1rem .65rem 2.5rem;
            border:2px solid var(--border); border-radius:10px;
            font-size:.92rem; font-family:'Inter',sans-serif;
            transition:all .25s; background:#fafafa;
        }
        .form-control:focus { outline:none; border-color:var(--primary); background:#fff; box-shadow:0 0 0 3px rgba(255,107,53,.12); }
        .form-control[type="file"] { padding:.5rem .8rem .5rem 2.5rem; cursor:pointer; }

        .btn {
            display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
            width:100%; padding:.75rem 1.5rem;
            border:none; border-radius:10px;
            font-size:.97rem; font-weight:700; cursor:pointer;
            transition:all .25s; font-family:'Inter',sans-serif; text-decoration:none;
        }
        .btn-primary { background:var(--primary); color:#fff; }
        .btn-primary:hover { background:var(--primary-dk); transform:translateY(-1px); box-shadow:0 6px 18px rgba(255,107,53,.35); }

        .alert {
            padding:.75rem 1.1rem; border-radius:8px; margin-bottom:1rem;
            font-weight:500; display:flex; align-items:center; gap:.5rem; font-size:.88rem;
        }
        .alert-danger  { background:#ffeaea; color:#c0392b; border-left:4px solid #dc3545; }
        .alert-success { background:#eafaf1; color:#1e8449; border-left:4px solid #28a745; }

        .auth-footer { text-align:center; margin-top:1.2rem; font-size:.88rem; color:var(--muted); }
        .auth-footer a { color:var(--primary); font-weight:600; text-decoration:none; }
        .auth-footer a:hover { text-decoration:underline; }

        .strength-bar { height:4px; border-radius:4px; margin-top:.4rem; background:#e9ecef; overflow:hidden; }
        .strength-fill { height:100%; border-radius:4px; transition:width .3s, background .3s; width:0; }

        @media(max-width:680px) {
            .auth-wrapper { grid-template-columns:1fr; }
            .auth-hero { display:none; }
            .auth-form-box { padding:2rem 1.2rem; }
            .form-row { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <!-- Hero Panel -->
    <div class="auth-hero">
        <div class="hero-icon"><i class="fa-solid fa-user-plus"></i></div>
        <h2>Join FoodRush!</h2>
        <p>Create a free account and enjoy fast delivery from local restaurants.</p>
        <ul>
            <li><i class="fa-solid fa-check-circle"></i> Browse 100+ restaurants</li>
            <li><i class="fa-solid fa-check-circle"></i> Real-time order tracking</li>
            <li><i class="fa-solid fa-check-circle"></i> Secure online payments</li>
            <li><i class="fa-solid fa-check-circle"></i> Exclusive member deals</li>
        </ul>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-box">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Fill in the details below to get started</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                <a href="index.php?action=login" style="margin-left:.5rem;color:inherit;font-weight:700;">Login →</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register" enctype="multipart/form-data" id="register-form" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="reg-name">Full Name</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" id="reg-name" name="name" class="form-control"
                               placeholder="John Doe" required
                               value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="reg-phone">Phone Number</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-phone input-icon"></i>
                        <input type="tel" id="reg-phone" name="phone" class="form-control"
                               placeholder="+880 1XX XXXX XXXX" required
                               value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg-email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" id="reg-email" name="email" class="form-control"
                           placeholder="you@example.com" required autocomplete="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="reg-password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="reg-password" name="password" class="form-control"
                               placeholder="Min. 6 characters" required autocomplete="new-password">
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="reg-confirm">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="reg-confirm" name="confirm" class="form-control"
                               placeholder="Repeat password" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg-pic">Profile Picture <span style="color:var(--muted);font-weight:400;">(optional, max 2MB)</span></label>
                <div class="input-wrap">
                    <i class="fa-solid fa-image input-icon"></i>
                    <input type="file" id="reg-pic" name="profile_pic" class="form-control" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="register-btn">
                <i class="fa-solid fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="index.php?action=login" id="go-login">Sign in</a>
        </div>
    </div>
</div>

<script>
    // Password strength indicator
    document.getElementById('reg-password').addEventListener('input', function() {
        const val = this.value;
        const fill = document.getElementById('strength-fill');
        let strength = 0;
        if (val.length >= 6)  strength++;
        if (val.length >= 10) strength++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;
        const colors = ['#dc3545','#fd7e14','#ffc107','#28a745'];
        const widths  = ['25%','50%','75%','100%'];
        fill.style.width = val.length ? widths[strength - 1] || '10%' : '0';
        fill.style.background = val.length ? colors[strength - 1] || '#dc3545' : 'transparent';
    });
</script>
</body>
</html>
