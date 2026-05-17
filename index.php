<?php
session_start();
require_once 'config/database.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'login':
    case 'register':
    case 'logout':
        require_once 'controllers/AuthController.php';
        $c = new AuthController();
        if ($action === 'login')         $c->login();
        elseif ($action === 'register')  $c->register();
        else                             $c->logout();
        break;

    case 'dashboard':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->dashboard();
        break;

    case 'restaurant':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->restaurant();
        break;

    case 'profile':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->profile();
        break;

    case 'addresses':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->addresses();
        break;

    case 'favourites':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->favourites();
        break;

    case 'reviews':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->myReviews();
        break;

    case 'complaints':
        require_once 'controllers/CustomerController.php';
        (new CustomerController())->complaints();
        break;

    case 'cart':
        require_once 'controllers/OrderController.php';
        (new OrderController())->cart();
        break;

    case 'checkout':
        require_once 'controllers/OrderController.php';
        (new OrderController())->checkout();
        break;

    case 'order_confirm':
        require_once 'controllers/OrderController.php';
        (new OrderController())->confirm();
        break;

    case 'order_detail':
        require_once 'controllers/OrderController.php';
        (new OrderController())->detail();
        break;

    case 'order_history':
        require_once 'controllers/OrderController.php';
        (new OrderController())->history();
        break;

    case 'cancel_order':
        require_once 'controllers/OrderController.php';
        (new OrderController())->cancel();
        break;

    case 'reorder':
        require_once 'controllers/OrderController.php';
        (new OrderController())->reorder();
        break;

    case 'submit_review':
        require_once 'controllers/OrderController.php';
        (new OrderController())->submitReview();
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
