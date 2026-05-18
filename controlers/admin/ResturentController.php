<?php

require_once '../../config/auth_check.php';
require_once '../../models/ResturentModel.php';

$restaurantModel = new RestaurantModel();

$action = $_GET['action'] ?? 'list';
$message = '';

// check ajax request
$isAjax = false;

$isAjax = (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);


if ($action == 'list') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $form_action = $_POST['form_action'];
        $id = $_POST['restaurant_id'];

        if ($form_action == 'suspend') {

            $restaurantModel->toggleApproval($id, 0);
            $message = "Restaurant suspended";

        } elseif ($form_action == 'reactivate') {

            $restaurantModel->toggleApproval($id, 1);
            $message = "Restaurant reactivated";
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                "status"  => "success",
                "message" => $message
            ]);
            exit;
        }
    }

    $restaurants = $restaurantModel->getAllRestaurants();

    require '../../views/admin/restaurants/list.php';
}



elseif ($action == 'pending') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $form_action = $_POST['form_action'];
        $id = $_POST['restaurant_id'];

        if ($form_action == 'approve') {

            $restaurantModel->toggleApproval($id, 1);
            $message = "Restaurant approved";

        } elseif ($form_action == 'reject') {

            $restaurantModel->deleteRestaurant($id);
            $message = "Restaurant rejected";
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                "status"  => "success",
                "message" => $message
            ]);
            exit;
        }
    }

    $pending_restaurants = $restaurantModel->getPendingRestaurants();

    require '../../views/admin/restaurants/pending.php';
}

?>