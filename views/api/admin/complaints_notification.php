<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../../models/ComplaintModel.php';

$model      = new ComplaintModel();
$complaints = $model->getAllComplaints();

$openCount = 0;
foreach ($complaints as $c) {
    if ($c['status'] === 'open') $openCount++;
}

echo json_encode([
    'count' => $openCount,
]);
