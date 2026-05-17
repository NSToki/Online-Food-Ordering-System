<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: /Food-Delevary-Site/views/admin/Auth/login.php");
    exit();
}
?>