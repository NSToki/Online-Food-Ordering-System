<?php
session_start();

require_once 'config/db.php';
require_once 'config/auth.php';

$route = isset($_GET['route']) ? $_GET['route'] : 'home';

$db = new Database();
$conn = $db->connect();

if (strpos($route, 'api/') === 0) {
    require_once 'api/ManagerApi.php';
    $api = new ManagerApi($conn);
    
    header('Content-Type: application/json');
    
    switch ($route) {
        case 'api/manager/active-orders':
            echo json_encode($api->getActiveOrders());
            break;
        case 'api/manager/update-order-status':
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($api->updateOrderStatus($data['order_id'] ?? 0, $data['status'] ?? ''));
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'API route not found']);
            break;
    }
    exit;
}

if (strpos($route, 'manager/') === 0) {
    require_once 'models/ManagerModel.php';
    require_once 'controllers/ManagerController.php';
    
    $model = new ManagerModel($conn);
    $controller = new ManagerController($model);
    
    switch ($route) {
        case 'manager/login':
            $controller->login();
            break;
        case 'manager/register':
            $controller->register();
            break;
        case 'manager/logout':
            $controller->logout();
            break;
        case 'manager/dashboard':
            $controller->dashboard();
            break;
        case 'manager/profile':
            $controller->profile();
            break;
        case 'manager/menu':
            $controller->menu();
            break;
        case 'manager/discounts':
            $controller->discounts();
            break;
        case 'manager/orders':
            $controller->orders();
            break;
        case 'manager/reviews':
            $controller->reviews();
            break;
        case 'manager/analytics':
            $controller->analytics();
            break;
        case 'manager/complaints':
            $controller->complaints();
            break;
        default:
            if (isManagerLoggedIn()) {
                header('Location: ?route=manager/dashboard');
            } else {
                header('Location: ?route=manager/login');
            }
            exit;
    }
    exit;
}

echo "<h1>Welcome to Online Food Ordering</h1>";
echo "<a href='?route=manager/login'>Restaurant Manager Login</a>";
