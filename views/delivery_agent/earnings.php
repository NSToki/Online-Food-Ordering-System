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

$agentModel = new DeliveryAgentModel($conn);
$earnings = $agentModel->getEarningsSummary($_SESSION["user_id"]);

$today = $earnings["today_earnings"] ?? 0;
$week = $earnings["week_earnings"] ?? 0;
$month = $earnings["month_earnings"] ?? 0;
$all_time = $earnings["all_time_earnings"] ?? 0;
$completed = $earnings["completed_deliveries"] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Earnings Summary</title>

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
            width: 85%;
            margin: 30px auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
            text-align: center;
        }

        .card h3 {
            margin: 0;
            color: #555;
        }

        .card p {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin: 12px 0 0;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Earnings Summary</h2>
    <a href="../../controllers/DeliveryAgentController.php?action=logout">Logout</a>
</div>

<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <div class="cards">

        <div class="card">
            <h3>Today</h3>
            <p><?php echo htmlspecialchars($today); ?> Tk</p>
        </div>

        <div class="card">
            <h3>This Week</h3>
            <p><?php echo htmlspecialchars($week); ?> Tk</p>
        </div>

        <div class="card">
            <h3>This Month</h3>
            <p><?php echo htmlspecialchars($month); ?> Tk</p>
        </div>

        <div class="card">
            <h3>All Time</h3>
            <p><?php echo htmlspecialchars($all_time); ?> Tk</p>
        </div>

        <div class="card">
            <h3>Completed Deliveries</h3>
            <p><?php echo htmlspecialchars($completed); ?></p>
        </div>

    </div>

</div>

</body>
</html>