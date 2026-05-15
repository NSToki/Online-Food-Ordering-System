<?php
require_once 'config/database.php';
require_once 'models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database        = new Database();
        $this->db        = $database->connect();
        $this->userModel = new User($this->db);
    }

    // ─── Login ───────────────────────────────────────────────────────────────
    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields.';
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_pic']  = $user['profile_pic'] ?? '';
                    header('Location: index.php?action=dashboard');
                    exit();
                } else {
                    $error = 'Invalid email or password. Please try again.';
                }
            }
        }

        require_once 'views/auth/login.php';
    }

    // ─── Register ────────────────────────────────────────────────────────────
    public function register() {
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name']     ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm  = trim($_POST['confirm']  ?? '');
            $phone    = trim($_POST['phone']    ?? '');

            if (empty($name) || empty($email) || empty($password) || empty($phone)) {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'An account with this email already exists.';
            } else {
                // Handle profile picture upload
                $profile_pic = 'assets/img/default_avatar.png';
                if (!empty($_FILES['profile_pic']['name'])) {
                    $upload_dir  = 'uploads/profiles/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    $ext         = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
                    $allowed     = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (in_array($ext, $allowed) && $_FILES['profile_pic']['size'] <= 2097152) {
                        $filename    = uniqid('pic_', true) . '.' . $ext;
                        $target      = $upload_dir . $filename;
                        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                            $profile_pic = $target;
                        }
                    } else {
                        $error = 'Profile picture must be JPG/PNG/GIF/WEBP under 2 MB.';
                    }
                }

                if (empty($error)) {
                    $result = $this->userModel->registerCustomer($name, $email, $password, $phone, $profile_pic);
                    if ($result) {
                        $success = 'Registration successful! You can now log in.';
                    } else {
                        $error = 'Registration failed. Please try again.';
                    }
                }
            }
        }

        require_once 'views/auth/register.php';
    }
}
?>
