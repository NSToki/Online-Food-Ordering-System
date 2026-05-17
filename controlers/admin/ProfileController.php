<?php

require_once '../../config/auth_check.php';
require_once '../../models/UserModel.php';

$userModel = new UserModel();

$adminId = $_SESSION['admin_id'] ?? null;

if (!$adminId) {
    header("Location: AuthController.php?action=login");
    exit;
}

$admin = $userModel->getAdminById($adminId);

if (!$admin) {
    die("Admin not found");
}

require '../../views/admin/profile/index.php';

?>