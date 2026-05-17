<?php

session_start();

require_once '../../models/UserModel.php';
require_once '../../models/SettingsModel.php';
require_once '../../models/ResturentModel.php';

$action = $_GET['action'] ?? 'login';

$user = new UserModel();
$settings = new SettingsModel();
$restaurant = new RestaurantModel();

$error = '';
$success = '';

// ---------------- LOGIN ----------------

if ($action == 'login') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($email && $password) {

            $admin = $user->getAdminByEmail($email);

            if ($admin && password_verify($password, $admin['password_hash'])) {

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];

                if (!empty($admin['profile_pic'])) {
                    $_SESSION['admin_profile_pic'] = base64_encode($admin['profile_pic']);
                }

                header("Location: DashboardController.php");
                exit;

            } else {
                $error = "Wrong email or password";
            }

        } else {
            $error = "Fill all fields";
        }
    }

    require '../../views/admin/Auth/login.php';
}


// ---------------- LOGOUT ----------------

elseif ($action == 'logout') {

    session_destroy();

    header("Location: AuthController.php?action=login");
    exit;
}


// ---------------- REGISTER RESTAURANT ----------------

elseif ($action == 'register') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';

        $restaurant_name = $_POST['restaurant_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $cuisine = $_POST['cuisine'] ?? '';

        if ($name && $email && $password && $phone && $restaurant_name && $address && $city) {

            if ($user->checkEmailExists($email)) {

                $error = "Email already exists";

            } else {

                try {

                    $manager_id = $user->createManager($name, $email, $password, $phone);

                    $restaurant->createRestaurant(
                        $manager_id,
                        $restaurant_name,
                        $cuisine,
                        $address,
                        $city
                    );

                    $success = "Registration successful (waiting approval)";

                } catch (Exception $e) {
                    $error = "Failed: " . $e->getMessage();
                }
            }

        } else {
            $error = "Fill all fields";
        }
    }

    $settings_data = $settings->getSettings();
    $cuisines = json_decode($settings_data['cuisine_categories'] ?? '[]', true);

    require '../../views/admin/Auth/register_restaurant.php';
}


// ---------------- REGISTER ADMIN ----------------

elseif ($action == 'register_admin') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';

        $image = null;

        if (!empty($_FILES['profile_pic']['tmp_name'])) {
            $image = file_get_contents($_FILES['profile_pic']['tmp_name']);
        }

        if ($name && $email && $password && $phone && $image) {

            if ($user->checkEmailExists($email)) {

                $error = "Email already exists";

            } else {

                try {

                    $user->createAdmin($name, $email, $password, $phone, $image);

                    $success = "Admin created successfully";

                } catch (Exception $e) {
                    $error = "Failed: " . $e->getMessage();
                }
            }

        } else {
            $error = "Fill all fields";
        }
    }

    require '../../views/admin/Auth/register_admin.php';
}

?>