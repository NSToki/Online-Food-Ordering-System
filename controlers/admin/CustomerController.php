<?php
require_once __DIR__ . '/../../config/auth_check.php';
require_once __DIR__ . '/../../models/UserModel.php';

$userModel = new UserModel();
$message = '';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'], $_POST['customer_id'])) {
    $formAction = $_POST['form_action'];
    $id = intval($_POST['customer_id']);
    
    if ($formAction === 'deactivate') {
        $userModel->toggleUserStatus($id, 0);
        $message = "Customer account deactivated.";
    } elseif ($formAction === 'reactivate') {
        $userModel->toggleUserStatus($id, 1);
        $message = "Customer account reactivated.";
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => $message]);
        exit;
    }
}

$customers = $userModel->getCustomers();
require_once __DIR__ . '/../../views/admin/customers/list.php';
?>
