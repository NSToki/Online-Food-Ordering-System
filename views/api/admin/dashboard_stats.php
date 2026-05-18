<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../../models/AnalyticsModel.php';

$analytics = new AnalyticsModel();
$data      = $analytics->getDashboardMetrics();

echo json_encode([
    'activeRestaurants' => (int) $data['activeRestaurants'],
    'ordersToday'       => (int) $data['ordersToday'],
    'totalUsers'        => (int) $data['totalUsers'],
    'activeAgents'      => (int) $data['activeAgents'],
    'totalRevenue'      => number_format((float) $data['totalRevenue'], 2),
]);
