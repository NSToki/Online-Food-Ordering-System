<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin | FoodPathai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --success:       #10B981;
            --success-bg:    rgba(16,185,129,0.10);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }

        .register-box {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.10);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #12111A, #2a1a0e);
            padding: 36px 40px 30px;
            text-align: center;
        }
        .register-header .logo-wrap {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: white;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(232,98,26,0.40);
        }
        .register-header h1 { color: white; font-size: 22px; font-weight: 800; }
        .register-header p  { color: rgba(255,255,255,0.50); font-size: 13px; margin-top: 5px; }

        .register-body { padding: 32px 40px 36px; }

        .alert {
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 22px;
        }
        .alert-error   { background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(239,68,68,0.20); }
        .alert-success { background: var(--success-bg); color: var(--success); border: 1px solid rgba(16,185,129,0.20); }

        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            color: var(--text);
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 14px;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px; color: var(--text);
            background: var(--bg);
            outline: none;
            transition: all 0.2s;
        }
        .input-wrap input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .input-wrap input[type="file"] {
            padding: 10px 14px 10px 40px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-register {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white; border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer;
            transition: all 0.22s;
            margin-top: 6px;
            box-shadow: 0 6px 20px rgba(232,98,26,0.30);
            display: flex; align-items: center;
            justify-content: center; gap: 9px;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(232,98,26,0.40); }

        .form-footer {
            text-align: center; margin-top: 20px;
            font-size: 13px; color: var(--text-muted);
        }
        .form-footer a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .form-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-box">
    <div class="register-header">
        <div class="logo-wrap"><i class="fa-solid fa-motorcycle"></i></div>
        <h1>Create Admin Account</h1>
        <p>Register a new FoodPathai administrator</p>
    </div>

    <div class="register-body">

        <?php if (!empty($error)) : ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data"
              action="/Food-Delevary-Site/controlers/admin/AuthController.php?action=register_admin"
              id="registerForm">

            <div class="form-group">
                <label class="form-label" for="reg_name">Full Name</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="reg_name" name="name" placeholder="Enter full name" required autocomplete="name">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg_email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" id="reg_email" name="email" placeholder="admin@foodpathai.com" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg_phone">Phone Number</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-phone"></i>
                    <input type="text" id="reg_phone" name="phone" placeholder="+880 1234-567890" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg_pic">Profile Picture</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-image"></i>
                    <input type="file" id="reg_pic" name="profile_pic" accept="image/*" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reg_pass">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="reg_pass" name="password" placeholder="Create a strong password" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn-register" id="registerBtn">
                <i class="fa-solid fa-user-plus"></i>
                Create Admin Account
            </button>

        </form>

        <p class="form-footer">
            Already have an account?
            <a href="/Food-Delevary-Site/controlers/admin/AuthController.php?action=login">Sign In</a>
        </p>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function() {
        const btn = document.getElementById('registerBtn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating Account...';
        btn.disabled = true;
    });
</script>

</body>
</html>