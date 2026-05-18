<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    header("Location: login.php");
    exit;
}

require_once "../../food_ordering_db/connection.php";
require_once "../../models/DeliveryAgentModel.php";

$model = new DeliveryAgentModel($conn);
$profile = $model->getAgentProfile($_SESSION["user_id"]);

$errors = $_SESSION["profile_errors"] ?? [];
unset($_SESSION["profile_errors"]);

$successMessage = $_SESSION["successMessage"] ?? "";
$errorMessage = $_SESSION["errorMessage"] ?? "";

unset($_SESSION["successMessage"]);
unset($_SESSION["errorMessage"]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <script src="../../assets/js/delivery_agent_validation.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background: #007bff;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .container {
            width: 500px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 9px;
            margin-top: 5px;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .back {
            display: inline-block;
            margin-bottom: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .profile-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>My Profile</h2>
    <a href="../../controllers/DeliveryAgentController.php?action=logout">Logout</a>
</div>

<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <?php if (!empty($successMessage)): ?>
        <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <?php if (!empty($profile["profile_pic"])): ?>
        <img class="profile-img" src="../../<?php echo htmlspecialchars($profile["profile_pic"]); ?>">
    <?php endif; ?>

    <form method="post" action="../../controllers/DeliveryAgentController.php?action=updateProfile" enctype="multipart/form-data" onsubmit="return validateProfileForm();">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($profile["name"]); ?>">
            <span class="error"><?php echo $errors["name"] ?? ""; ?></span>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($profile["email"]); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($profile["phone"]); ?>">
            <span class="error"><?php echo $errors["phone"] ?? ""; ?></span>
        </div>

        <div class="form-group">
            <label>Vehicle Type</label>
            <select name="vehicle_type" id="vehicle_type">
                <option value="">Select Vehicle</option>
                <option value="Bike" <?php if ($profile["vehicle_type"] == "Bike") echo "selected"; ?>>Bike</option>
                <option value="Cycle" <?php if ($profile["vehicle_type"] == "Cycle") echo "selected"; ?>>Cycle</option>
                <option value="Car" <?php if ($profile["vehicle_type"] == "Car") echo "selected"; ?>>Car</option>
            </select>
            <span class="error"><?php echo $errors["vehicle_type"] ?? ""; ?></span>
        </div>

        <div class="form-group">
            <label>Current Location</label>
            <textarea name="current_location_text" id="current_location_text"><?php echo htmlspecialchars($profile["current_location_text"] ?? ""); ?></textarea>
            <span class="error"><?php echo $errors["current_location_text"] ?? ""; ?></span>
        </div>

        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic" id="profile_pic">
            <span class="error"><?php echo $errors["profile_pic"] ?? ""; ?></span>
        </div>

        <button type="submit">Update Profile</button>

    </form>

</div>

</body>
</html>