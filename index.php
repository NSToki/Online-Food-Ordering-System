<?php
session_start();
require_once 'config/database.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
    case 'register':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();
        $action === 'login' ? $auth->login() : $auth->register();
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();

    case 'dashboard':
    case 'menu':
        require_once 'controllers/CustomerController.php';
        $cust = new CustomerController();
        $action === 'dashboard' ? $cust->dashboard() : $cust->menu();
        break;

    case 'checkout':
    case 'order_history':
        require_once 'controllers/OrderController.php';
        $ord = new OrderController();
        $action === 'checkout' ? $ord->checkout() : $ord->history();
        break;

    case 'ajax_add_cart':
    case 'ajax_remove_cart':
    case 'ajax_clear_cart':
        require_once 'controllers/AjaxController.php';
        break;

    default:
        $redirect = isset($_SESSION['user_id']) ? 'dashboard' : 'login';
        header("Location: index.php?action=" . $redirect);
        exit();
}
?>
