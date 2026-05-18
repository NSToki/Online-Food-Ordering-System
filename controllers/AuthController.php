<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User(getDB());
    }

    public function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name']     ?? '');
            $email = trim($_POST['email']    ?? '');
            $phone = trim($_POST['phone']    ?? '');
            $pass  =      $_POST['password'] ?? '';

            if ($name === '' || $email === '' || $phone === '' || $pass === '') {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif (strlen($pass) < 6) {
                $error = "Password must be at least 6 characters.";
            } elseif ($this->userModel->emailExists($email)) {
                $error = "This email is already registered.";
            } else {
                $pic = null;
                if (!empty($_FILES['profile_pic']['name'])) {
                    $dir = 'assets/uploads/';
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    $fname = time() . '_' . basename($_FILES['profile_pic']['name']);
                    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $dir . $fname);
                    $pic = $fname;
                }
                if ($this->userModel->registerCustomer($name, $email, $pass, $phone, $pic)) {
                    header("Location: index.php?action=login&registered=1"); exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
        require_once 'views/auth/register.php';
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']    ?? '');
            $pass  =      $_POST['password'] ?? '';
            $user  = $this->userModel->login($email, $pass);
            if ($user) {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['name']        = $user['name'];
                $_SESSION['role']        = $user['role'];
                $_SESSION['profile_pic'] = $user['profile_pic'];
                header("Location: index.php?action=dashboard"); exit();
            } else {
                $error = "Invalid email or password.";
            }
        }
        require_once 'views/auth/login.php';
    }

    public function logout() {
        session_unset(); session_destroy();
        header("Location: index.php?action=login"); exit();
    }
}
?>
