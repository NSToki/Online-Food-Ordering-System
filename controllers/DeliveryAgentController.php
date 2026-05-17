<?php

session_start();

require_once "../food_ordering_db/connection.php";
require_once "../models/DeliveryAgentModel.php";

$model = new DeliveryAgentModel($conn);

$action = $_GET["action"] ?? "";

if ($action == "register") {
    registerDeliveryAgent($model);
} elseif ($action == "login") {
    loginDeliveryAgent($model);
} elseif ($action == "updateProfile") {
    updateProfile($model);
} elseif ($action == "logout") {
    logoutDeliveryAgent();
} else {
    header("Location: ../views/delivery_agent/login.php");
    exit;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function registerDeliveryAgent($model) {
    $errors = [];

    $name = "";
    $email = "";
    $phone = "";
    $vehicle_type = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = sanitizeInput($_POST["name"] ?? "");
        $email = sanitizeInput($_POST["email"] ?? "");
        $phone = sanitizeInput($_POST["phone"] ?? "");
        $vehicle_type = sanitizeInput($_POST["vehicle_type"] ?? "");
        $password = $_POST["password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";

        if (empty($name)) {
            $errors["name"] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors["name"] = "Only letters and spaces allowed";
        }

        if (empty($email)) {
            $errors["email"] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format";
        } elseif ($model->emailExists($email)) {
            $errors["email"] = "Email already exists";
        }

        if (empty($phone)) {
            $errors["phone"] = "Phone is required";
        } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $errors["phone"] = "Phone must be 10 to 15 digits";
        }

        if (empty($vehicle_type)) {
            $errors["vehicle_type"] = "Vehicle type is required";
        }

        if (empty($password)) {
            $errors["password"] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors["password"] = "Password must be at least 8 characters";
        } elseif (!preg_match("/[@#$%]/", $password)) {
            $errors["password"] = "Password must contain @, #, $, or %";
        }

        if (empty($confirm_password)) {
            $errors["confirm_password"] = "Confirm password is required";
        } elseif ($password !== $confirm_password) {
            $errors["confirm_password"] = "Passwords do not match";
        }

        if (empty($errors)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $isRegistered = $model->registerAgent(
                $name,
                $email,
                $password_hash,
                $phone,
                $vehicle_type
            );

            if ($isRegistered) {
                $_SESSION["successMessage"] = "Registration successful. Please wait for admin approval before login.";
                header("Location: ../views/delivery_agent/login.php");
                exit;
            } else {
                $errors["register"] = "Registration failed. Please try again.";
            }
        }

        include "../views/delivery_agent/register.php";
        exit;
    }

    header("Location: ../views/delivery_agent/register.php");
    exit;
}

function loginDeliveryAgent($model) {
    $errors = [];
    $email = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = sanitizeInput($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $remember_me = $_POST["remember_me"] ?? "";

        if (empty($email)) {
            $errors["email"] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format";
        }

        if (empty($password)) {
            $errors["password"] = "Password is required";
        }

        if (empty($errors)) {
            $user = $model->getAgentByEmail($email);

            if (!$user) {
                $errors["login"] = "Invalid email or password";
            } elseif (!password_verify($password, $user["password_hash"])) {
                $errors["login"] = "Invalid email or password";
            } elseif ($user["role"] !== "agent") {
                $errors["login"] = "Only delivery agents can login here";
            } elseif ($user["is_active"] != 1) {
                $errors["login"] = "Your account is inactive";
            } elseif ($user["is_approved"] != 1) {
                $errors["login"] = "Your delivery agent account is waiting for admin approval";
            } else {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["agent_id"] = $user["agent_id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                if ($remember_me == "1") {
                    setcookie("delivery_agent_email", $email, time() + (7 * 24 * 60 * 60), "/");
                } else {
                    setcookie("delivery_agent_email", "", time() - 3600, "/");
                }

                header("Location: ../views/delivery_agent/dashboard.php");
                exit;
            }
        }

        include "../views/delivery_agent/login.php";
        exit;
    }

    header("Location: ../views/delivery_agent/login.php");
    exit;
}

function updateProfile($model) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
        header("Location: ../views/delivery_agent/login.php");
        exit;
    }

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION["user_id"];

        $name = sanitizeInput($_POST["name"] ?? "");
        $phone = sanitizeInput($_POST["phone"] ?? "");
        $vehicle_type = sanitizeInput($_POST["vehicle_type"] ?? "");
        $current_location_text = sanitizeInput($_POST["current_location_text"] ?? "");

        if (empty($name)) {
            $errors["name"] = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors["name"] = "Only letters and spaces allowed";
        }

        if (empty($phone)) {
            $errors["phone"] = "Phone is required";
        } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $errors["phone"] = "Phone must be 10 to 15 digits";
        }

        if (empty($vehicle_type)) {
            $errors["vehicle_type"] = "Vehicle type is required";
        }

        if (empty($current_location_text)) {
            $errors["current_location_text"] = "Current location is required";
        }

        $profile_pic = "";

        if (!empty($_FILES["profile_pic"]["name"])) {
            $allowed_types = ["image/jpeg", "image/png", "image/jpg"];

            if (!in_array($_FILES["profile_pic"]["type"], $allowed_types)) {
                $errors["profile_pic"] = "Only JPG and PNG images are allowed";
            } else {
                $upload_dir = "../assets/uploads/profile_pics/";

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    $profile_pic = "assets/uploads/profile_pics/" . $file_name;
                } else {
                    $errors["profile_pic"] = "Image upload failed";
                }
            }
        }

        if (empty($errors)) {
            $updated = $model->updateAgentProfile(
                $user_id,
                $name,
                $phone,
                $vehicle_type,
                $current_location_text,
                $profile_pic
            );

            if ($updated) {
                $_SESSION["name"] = $name;
                $_SESSION["successMessage"] = "Profile updated successfully";
                header("Location: ../views/delivery_agent/profile.php");
                exit;
            } else {
                $_SESSION["errorMessage"] = "Profile update failed";
            }
        }

        $_SESSION["profile_errors"] = $errors;
        header("Location: ../views/delivery_agent/profile.php");
        exit;
    }

    header("Location: ../views/delivery_agent/profile.php");
    exit;
}

function logoutDeliveryAgent() {
    session_unset();
    session_destroy();

    header("Location: ../views/delivery_agent/login.php");
    exit;
}

function checkDeliveryAgentLogin() {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
        header("Location: ../views/delivery_agent/login.php");
        exit;
    }
}

?>