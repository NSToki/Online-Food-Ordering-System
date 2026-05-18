<?php

require_once '../../config/auth_check.php';
require_once '../../models/DeliveryAgentModel.php';

$action = $_GET['action'] ?? 'list';

$agent = new DeliveryAgentModel();

function jsonResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode([
        "status"  => $status,
        "message" => $message
    ]);
    exit;
}


if ($action == 'list') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $form_action = $_POST['form_action'] ?? '';
        $agent_id = $_POST['agent_id'] ?? 0;

        switch ($form_action) {

            case 'deactivate':
                $agent->toggleUserStatusByAgentId($agent_id, 0);
                jsonResponse('success', 'Agent Deactivated');
                break;

            case 'reactivate':
                $agent->toggleUserStatusByAgentId($agent_id, 1);
                jsonResponse('success', 'Agent Reactivated');
                break;
        }
    }

    $agents = $agent->getApprovedAgents();

    require_once '../../views/admin/agents/list.php';
}


if ($action == 'pending') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $form_action = $_POST['form_action'] ?? '';
        $agent_id = $_POST['agent_id'] ?? 0;

        switch ($form_action) {

            case 'approve':
                $agent->approveAgent($agent_id);
                jsonResponse('success', 'Agent Approved');
                break;

            case 'reject':
                $agent->rejectAgent($agent_id);
                jsonResponse('success', 'Agent Rejected');
                break;
        }
    }

    $pending_agents = $agent->getPendingAgents();

    require_once '../../views/admin/agents/pending.php';
}
?>