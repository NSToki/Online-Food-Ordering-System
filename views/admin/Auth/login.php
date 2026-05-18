<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | FoodPathai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FoodPathai Admin Login - Secure platform access">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --primary:       #E8621A;
            --primary-light: #F5A623;
            --primary-glow:  rgba(232,98,26,0.18);
            --text:          #1A1A2E;
            --text-muted:    #6B7280;
            --border:        #E8EAF0;
            --bg:            #F4F5F9;
            --danger:        #EF4444;
            --danger-bg:     rgba(239,68,68,0.10);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        /* --- LEFT PANEL (brand) --- */
        .login-brand {
            background: linear-gradient(145deg, #12111A 0%, #1e1b2e 50%, #2a1a0e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(232,98,26,0.20) 0%, transparent 70%);
            top: -100px; right: -100px;
            border-radius: 50%;
        }
        .login-brand::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(245,166,35,0.15) 0%, transparent 70%);
            bottom: -60px; left: -60px;
            border-radius: 50%;
        }

        .brand-logo-wrap {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px;
            color: white;
            margin-bottom: 28px;
            box-shadow: 0 20px 60px rgba(232,98,26,0.40);
            position: relative; z-index: 1;
        }

        .brand-title {
            font-size: 38px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            position: relative; z-index: 1;
            text-align: center;
        }
        .brand-title span { color: var(--primary-light); }

        .brand-desc {
            font-size: 15px;
            color: rgba(255,255,255,0.50);
            margin-top: 14px;
            text-align: center;
            max-width: 320px;
            line-height: 1.7;
            position: relative; z-index: 1;
        }

        .brand-stats {
            display: flex;
            gap: 40px;
            margin-top: 50px;
            position: relative; z-index: 1;
        }

        .brand-stat { text-align: center; }
        .brand-stat-val {
            font-size: 28px;
            font-weight: 800;
            color: white;
        }
        .brand-stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            margin-top: 3px;
        }

        /* --- RIGHT PANEL (form) --- */
        .login-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 80px;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.3px;
        }
        .login-box .login-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 6px;
            margin-bottom: 34px;
        }

        .alert-error {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239,68,68,0.20);
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 22px;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }
        .input-wrap input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text);
            outline: none;
            transition: all 0.2s;
            background: var(--bg);
        }
        .input-wrap input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.22s;
            margin-top: 8px;
            box-shadow: 0 6px 24px rgba(232,98,26,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(232,98,26,0.45);
        }
        .btn-login:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .form-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .form-footer a:hover { text-decoration: underline; }

        /* --- RESPONSIVE --- */
        @media (max-width: 900px) {
            body { grid-template-columns: 1fr; }
            .login-brand { display: none; }
            .login-form-panel { padding: 40px 24px; }
        }
    </style>
</head>
<body>

<!-- LEFT: Brand Panel -->
<div class="login-brand">
    <div class="brand-logo-wrap">
        <i class="fa-solid fa-motorcycle"></i>
    </div>
    <h1 class="brand-title">Food<span>Pathai</span></h1>
    <p class="brand-desc">
        Your all-in-one platform to manage restaurants, orders, delivery agents and more.
    </p>
    <div class="brand-stats">
        <div class="brand-stat">
            <div class="brand-stat-val">500+</div>
            <div class="brand-stat-label">Restaurants</div>
        </div>
        <div class="brand-stat">
            <div class="brand-stat-val">12K</div>
            <div class="brand-stat-label">Orders / Day</div>
        </div>
        <div class="brand-stat">
            <div class="brand-stat-val">98%</div>
            <div class="brand-stat-label">Uptime</div>
        </div>
    </div>
</div>

<!-- RIGHT: Form Panel -->
<div class="login-form-panel">
    <div class="login-box">
        <h2>Welcome back 👋</h2>
        <p class="login-subtitle">Sign in to your admin account to continue</p>

        <?php if (!empty($error)) : ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/Food-Delevary-Site/controlers/admin/AuthController.php?action=login" id="loginForm">

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="admin@foodpathai.com" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <i class="fa-solid fa-right-to-bracket"></i>
                Sign In
            </button>

        </form>

        <p class="form-footer">
            New admin? <a href="/Food-Delevary-Site/controlers/admin/AuthController.php?action=register_admin">Register Admin Account</a>
        </p>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Signing In...';
        btn.disabled = true;
    });
</script>

</body>
</html>