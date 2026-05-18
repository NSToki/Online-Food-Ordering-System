<?php
// This view only handles UI.
// Database, session, and authentication logic stay in controller.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$email = $email ?? "";
$errors = $errors ?? [];
$successMessage = $successMessage ?? "";

if (isset($_SESSION["successMessage"])) {
    $successMessage = $_SESSION["successMessage"];
    unset($_SESSION["successMessage"]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <script src="/Online-Food-Ordering-System/assets/js/delivery_agent_validation.js"></script>
    <title>Delivery Agent Login</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container{
            width: 400px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .login-container h2{
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group{
            margin-bottom: 18px;
        }

        .form-group label{
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: bold;
        }

        .form-group input{
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus{
            border-color: #007bff;
            outline: none;
        }

        .error{
            color: red;
            font-size: 13px;
        }

        .success{
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }

        .remember-box{
            margin-bottom: 18px;
        }

        .login-btn{
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-btn:hover{
            background-color: #0056b3;
        }

        .bottom-text{
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .bottom-text a{
            color: #007bff;
            text-decoration: none;
        }

        .bottom-text a:hover{
            text-decoration: underline;
        }

        .main-error{
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

    <div class="login-container">

        <h2>Delivery Agent Login</h2>

        <?php if (!empty($successMessage)): ?>
            <p class="success">
                <?php echo htmlspecialchars($successMessage); ?>
            </p>
        <?php endif; ?>

        <?php if (isset($errors["login"])): ?>
            <p class="main-error">
                <?php echo htmlspecialchars($errors["login"]); ?>
            </p>
        <?php endif; ?>

        <form id="loginForm"
              action="/Online-Food-Ordering-System/controllers/DeliveryAgentController.php?action=login"
              method="post" onsubmit="return validateLoginForm();">

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    value="<?php echo htmlspecialchars($email); ?>" required 
                >

                <span class="error" id="emailError">
                    <?php echo $errors["email"] ?? ""; ?>
                </span>

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                >

                <span class="error" id="passwordError">
                    <?php echo $errors["password"] ?? ""; ?>
                </span>

            </div>

            <div class="remember-box">

                <input
                    type="checkbox"
                    name="remember_me"
                    id="remember_me"
                    value="1"
                >

                <label for="remember_me">Remember Me</label>

            </div>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

        <div class="bottom-text">
            Don’t have an account?
            <a href="/Online-Food-Ordering-System/views/delivery_agent/register.php">Register Here</a>
        </div>
    </div>
</body>

</html>