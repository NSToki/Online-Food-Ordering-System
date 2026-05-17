<?php
require_once 'models/User.php';
require_once 'models/Restaurant.php';
require_once 'models/Address.php';
require_once 'models/SavedRestaurant.php';
require_once 'models/Review.php';
require_once 'models/Complaint.php';

class CustomerController {

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }
        if (($_SESSION['role'] ?? '') !== 'customer') {
            header("Location: index.php?action=login"); exit();
        }
    }

    // ── DASHBOARD: browse/search restaurants ──────────────────────
    public function dashboard() {
        $this->requireLogin();
        $db    = getDB();
        $model = new Restaurant($db);

        $search  = trim($_GET['search']  ?? '');
        $cuisine = trim($_GET['cuisine'] ?? '');
        $city    = trim($_GET['city']    ?? '');

        $restaurants   = $model->getAll($search, $cuisine, $city);
        $cuisine_types = $model->getCuisineTypes();
        $cities        = $model->getCities();

        // Which restaurants has this customer saved?
        $savedModel = new SavedRestaurant($db);
        $savedAll   = $savedModel->getAll($_SESSION['user_id']);
        $savedIds   = array_column($savedAll, 'id');   // restaurant IDs

        require_once 'views/customer/dashboard.php';
    }

    // ── RESTAURANT DETAIL + MENU ───────────────────────────────────
    public function restaurant() {
        $this->requireLogin();
        $db    = getDB();
        $model = new Restaurant($db);

        $restaurant_id = intval($_GET['id'] ?? 0);
        $restaurant    = $model->getById($restaurant_id);
        if (!$restaurant) {
            header("Location: index.php?action=dashboard"); exit();
        }

        $categories = $model->getCategories($restaurant_id);
        $menu_items = $model->getMenuItems($restaurant_id);
        $reviews    = $model->getReviews($restaurant_id);

        $savedModel = new SavedRestaurant($db);
        $is_saved   = $savedModel->isSaved($_SESSION['user_id'], $restaurant_id);

        require_once 'views/customer/restaurant.php';
    }

    // ── PROFILE ───────────────────────────────────────────────────
    public function profile() {
        $this->requireLogin();
        $db        = getDB();
        $userModel = new User($db);
        $user      = $userModel->getById($_SESSION['user_id']);

        $success = $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['form_type'] ?? '';

            if ($type === 'profile') {
                $name  = trim($_POST['name']  ?? '');
                $phone = trim($_POST['phone'] ?? '');
                if ($name === '' || $phone === '') {
                    $error = "Name and phone cannot be empty.";
                } else {
                    $pic = null;
                    if (!empty($_FILES['profile_pic']['name'])) {
                        $dir = 'assets/uploads/';
                        if (!is_dir($dir)) mkdir($dir, 0777, true);
                        $fname = time() . '_' . basename($_FILES['profile_pic']['name']);
                        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $dir . $fname);
                        $pic = $fname;
                    }
                    $userModel->updateProfile($_SESSION['user_id'], $name, $phone, $pic);
                    $_SESSION['name'] = $name;
                    $user = $userModel->getById($_SESSION['user_id']);
                    $success = "Profile updated successfully.";
                }
            } elseif ($type === 'password') {
                $old  = $_POST['old_password']  ?? '';
                $new  = $_POST['new_password']  ?? '';
                $conf = $_POST['confirm_password'] ?? '';
                if (!password_verify($old, $user['password_hash'])) {
                    $error = "Current password is incorrect.";
                } elseif (strlen($new) < 6) {
                    $error = "New password must be at least 6 characters.";
                } elseif ($new !== $conf) {
                    $error = "Passwords do not match.";
                } else {
                    $userModel->changePassword($_SESSION['user_id'], $new);
                    $success = "Password changed successfully.";
                }
            }
        }
        require_once 'views/customer/profile.php';
    }

    // ── SAVED ADDRESSES ───────────────────────────────────────────
    public function addresses() {
        $this->requireLogin();
        $db    = getDB();
        $model = new Address($db);
        $success = $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $act = $_POST['addr_action'] ?? '';
            if ($act === 'add') {
                $label   = trim($_POST['label']        ?? '');
                $addr    = trim($_POST['address_line'] ?? '');
                $city    = trim($_POST['city']         ?? '');
                $def     = isset($_POST['is_default']) ? 1 : 0;
                if ($label === '' || $addr === '' || $city === '') {
                    $error = "All fields required.";
                } else {
                    $model->add($_SESSION['user_id'], $label, $addr, $city, $def);
                    $success = "Address added.";
                }
            } elseif ($act === 'delete') {
                $model->delete(intval($_POST['addr_id']), $_SESSION['user_id']);
                $success = "Address deleted.";
            } elseif ($act === 'default') {
                $model->setDefault(intval($_POST['addr_id']), $_SESSION['user_id']);
                $success = "Default address updated.";
            }
        }

        $addresses = $model->getAll($_SESSION['user_id']);
        require_once 'views/customer/addresses.php';
    }

    // ── FAVOURITES ────────────────────────────────────────────────
    public function favourites() {
        $this->requireLogin();
        $db    = getDB();
        $model = new SavedRestaurant($db);
        $restaurants = $model->getAll($_SESSION['user_id']);
        require_once 'views/customer/favourites.php';
    }

    // ── MY REVIEWS ────────────────────────────────────────────────
    public function myReviews() {
        $this->requireLogin();
        $db      = getDB();
        $model   = new Review($db);
        $reviews = $model->getByCustomer($_SESSION['user_id']);
        require_once 'views/customer/reviews.php';
    }

    // ── COMPLAINTS ────────────────────────────────────────────────
    public function complaints() {
        $this->requireLogin();
        $db      = getDB();
        $model   = new Complaint($db);
        $success = $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject']     ?? '');
            $desc    = trim($_POST['description'] ?? '');
            if ($subject === '' || $desc === '') {
                $error = "Subject and description are required.";
            } else {
                $model->submit($_SESSION['user_id'], $subject, $desc);
                $success = "Complaint submitted successfully.";
            }
        }

        $complaints = $model->getByCustomer($_SESSION['user_id']);
        require_once 'views/customer/complaints.php';
    }
}
?>
