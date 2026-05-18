<?php

require_once '../../config/auth_check.php';
require_once '../../models/ComplaintModel.php';

$complaintModel = new ComplaintModel();

// check request type
$isAjax = false;

$isAjax = (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

// handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['form_action'];
    $id = $_POST['complaint_id'];

    if ($action == 'resolve') {

        $complaintModel->resolveComplaint($id);

        $message = "Complaint resolved";

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                "status"  => "success",
                "message" => $message
            ]);
            exit;
        }
    }
}

// get complaints list
$complaints = $complaintModel->getAllComplaints();

// load view
require_once '../../views/admin/complaints/list.php';

?>