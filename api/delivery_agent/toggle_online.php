<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access"
    ]);
    exit;
}

require_once "../../food_ordering_db/connection.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT is_online FROM delivery_agents WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$agent = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$agent) {
    echo json_encode([
        "success" => false,
        "message" => "Delivery agent not found"
    ]);
    exit;
}

$new_status = $agent["is_online"] == 1 ? 0 : 1;

$updateSql = "UPDATE delivery_agents SET is_online = ? WHERE user_id = ?";
$updateStmt = mysqli_prepare($conn, $updateSql);
mysqli_stmt_bind_param($updateStmt, "ii", $new_status, $user_id);

if (mysqli_stmt_execute($updateStmt)) {
    echo json_encode([
        "success" => true,
        "is_online" => $new_status,
        "message" => $new_status == 1 ? "You are now online" : "You are now offline"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Status update failed"
    ]);
}

mysqli_stmt_close($updateStmt);
?>