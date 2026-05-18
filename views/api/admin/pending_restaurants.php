<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../../models/ResturentModel.php';

$model   = new RestaurantModel();
$pending = $model->getPendingRestaurants();

echo json_encode([
    'count' => count($pending),
]);
