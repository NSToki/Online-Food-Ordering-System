<?php
session_start();
require_once 'config/database.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'login':
        require_once 'controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();

    case 'dashboard':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->dashboard();
        break;

    case 'menu':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->menu();
        break;

    case 'cart':
        require_once 'controllers/OrderController.php';
        (new OrderController())->cart();
        break;

    case 'checkout':
        require_once 'controllers/OrderController.php';
        (new OrderController())->checkout();
        break;

    case 'order_history':
        require_once 'controllers/OrderController.php';
        (new OrderController())->history();
        break;

    case 'ajax':
        require_once 'controllers/AjaxController.php';
        break;

    case 'home':
    default:
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
        } else {
            header("Location: index.php?action=login");
        }
        exit();
}
?>
