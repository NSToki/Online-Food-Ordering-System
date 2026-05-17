<?php

require_once __DIR__ . '/../config/database.php';

class DeliveryAgentModel {

	private $db = null;

	function __construct()
	{
		$this->db = getDB();
	}



	public function getApprovedAgents() {

		$sql = "SELECT da.id,
					   da.vehicle_type,
					   da.is_online,
					   da.is_approved,
					   u.name,
					   u.email,
					   u.is_active
				FROM delivery_agents da
				JOIN users u ON da.user_id = u.id
				WHERE da.is_approved = 1";

		$result = $this->db->query($sql);

		$agents = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$agents[] = $row;
			}
		}

		return $agents;
	}



	public function getPendingAgents() {

		$sql = "SELECT da.id,
					   da.vehicle_type,
					   u.name,
					   u.email
				FROM delivery_agents da
				JOIN users u ON da.user_id = u.id
				WHERE da.is_approved = 0";

		$result = $this->db->query($sql);

		$agents = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$agents[] = $row;
			}
		}

		return $agents;
	}


	public function approveAgent($id) {

		$sql = "UPDATE delivery_agents 
				SET is_approved = 1 
				WHERE id = '" . $id . "'";

		return $this->db->query($sql);
	}


	public function rejectAgent($id) {



		$sql = "SELECT user_id 
				FROM delivery_agents 
				WHERE id = '" . $id . "'";

		$result = $this->db->query($sql);

		$userId = $result->fetchColumn();



		$this->db->query("DELETE FROM delivery_agents WHERE id = '" . $id . "'");



		if ($userId) {

			$this->db->query("DELETE FROM users WHERE id = '" . $userId . "'");
		}

		return true;
	}


	public function toggleUserStatusByAgentId($agentId, $isActive) {

		$sql = "UPDATE users
				SET is_active = '" . $isActive . "'
				WHERE id = (
					SELECT user_id
					FROM delivery_agents
					WHERE id = '" . $agentId . "'
				)";

		return $this->db->query($sql);
	}
}

?>