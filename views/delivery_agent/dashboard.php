<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    header("Location: login.php");
    exit;
}

$name = $_SESSION["name"] ?? "Delivery Agent";

require_once "../../food_ordering_db/connection.php";
require_once "../../models/DeliveryAgentModel.php";

$agentModel = new DeliveryAgentModel($conn);
$is_online = $agentModel->getOnlineStatus($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html>
<head>
    <script src="../../assets/js/delivery_agent_ajax.js"></script>
    <title>Delivery Agent Dashboard</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background-color: #f4f6f9;
        }

        .navbar{
            background-color: #007bff;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2{
            font-size: 22px;
        }

        .navbar a{
            color: white;
            text-decoration: none;
            background-color: #dc3545;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .container{
            width: 90%;
            margin: 30px auto;
        }

        .welcome-box{
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }

        .welcome-box h3{
            margin-bottom: 8px;
            color: #333;
        }

        .card-area{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card{
            background-color: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
            text-align: center;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            min-height: 220px;
        }

        .card h3{
            color: #333;
            margin-bottom: 12px;
        }

        .card p{
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .card a{
            display: inline-block;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 9px 14px;
            border-radius: 5px;

            margin-top: auto;
            align-self: center;
        }

        .card a:hover{
            background-color: #0056b3;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .online {
            color: white;
            background-color: green;
        }

        .offline {
            color: white;
            background-color: gray;
        }

        .toggle-btn {
            margin-top: 12px;
            padding: 9px 14px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        .notification {
        display: none;
        background: #28a745;
        color: white;
        padding: 14px;
        border-radius: 5px;
        margin-top: 15px;
        font-weight: bold;
        text-decoration: none;
        }


    </style>
</head>

<body>
    

    <div class="navbar">
        <h2>Delivery Agent Panel</h2>
        <a href="../../controllers/DeliveryAgentController.php?action=logout">Logout</a>
    </div>

    <div class="container">

        <div class="welcome-box">
            <h3>Welcome, <?php echo htmlspecialchars($name); ?></h3>
            <p>This is your delivery agent dashboard. Manage your deliveries, earnings, and profile from here.</p>
            <br>
            <a href="available_orders.php"
            id="assignmentNotification"
            class="notification">

                🔔 New delivery assignment available!

            </a>
            <br>
            <br>
            <p>
                Current Status:
                <span id="onlineStatusText" class="status <?php echo $is_online == 1 ? 'online' : 'offline'; ?>">
                <?php echo $is_online == 1 ? "Online" : "Offline"; ?>
                </span>
            </p>
            <button id="toggleOnlineBtn" class="toggle-btn" onclick="toggleOnlineStatus()">
            <?php echo $is_online == 1 ? "Go Offline" : "Go Online"; ?>
            </button>
        </div>
        <div class="card-area">

            <div class="card">
                <h3>My Profile</h3>
                <p>Update your personal information, vehicle type, and location.</p>
                <a href="profile.php">Open</a>
            </div>

            <div class="card">
                <h3>Available Orders</h3>
                <p>View ready orders and accept delivery assignments.</p>
                <a href="available_orders.php">Open</a>
            </div>

            <div class="card">
                <h3>Active Delivery</h3>
                <p>View your current delivery and update delivery status.</p>
                <a href="active_delivery.php">Open</a>
            </div>

            <div class="card">
                <h3>Delivery History</h3>
                <p>See your completed deliveries.</p>
                <a href="history.php">Open</a>
            </div>

            <div class="card">
                <h3>Earnings</h3>
                <p>Check today, weekly, monthly, and total earnings.</p>
                <a href="earnings.php">Open</a>
            </div>

            <div class="card">
                <h3>Performance</h3>
                <p>View completed deliveries, average time, and complaints.</p>
                <a href="performance.php">Open</a>
            </div>

        </div>

    </div>
    <script>

    checkNewAssignments();

    setInterval(function () {
        checkNewAssignments();
    }, 5000);

    </script>

</body>
</html>