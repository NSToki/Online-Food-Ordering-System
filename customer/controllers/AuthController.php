<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->userModel = new User($db);
    }

    // Show register form / handle register POST
    public function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name     = htmlspecialchars(trim($_POST['name']));
            $email    = trim($_POST['email']);
            $phone    = htmlspecialchars(trim($_POST['phone']));
            $password = $_POST['password'];

            if (empty($name) || empty($email) || empty($password) || empty($phone)) {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif ($this->userModel->emailExists($email)) {
                $error = "Email is already registered.";
            } else {
                // Handle optional profile picture upload
                $profile_pic = null;
                if (!empty($_FILES['profile_pic']['name'])) {
                    $upload_dir = 'assets/uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                    $filename   = time() . '_' . basename($_FILES['profile_pic']['name']);
                    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $filename);
                    $profile_pic = $filename;
                }

                if ($this->userModel->registerCustomer($name, $email, $password, $phone, $profile_pic)) {
                    header("Location: index.php?action=login&registered=1");
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
        require_once 'views/auth/register.php';
    }

    // Show login form / handle login POST
    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email    = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['role']    = $user['role'];
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        }
        require_once 'views/auth/login.php';
    }
}
?>
