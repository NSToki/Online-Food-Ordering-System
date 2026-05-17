```php id="r9k2wp"
<?php

require_once __DIR__ . '/../config/database.php';

class RestaurantModel {

	private $db = null;

	function __construct()
	{
		$this->db = getDB();
	}


	public function createRestaurant($managerId, $name, $cuisine, $address, $city) {

		$sql = "INSERT INTO restaurants (
					manager_id,
					name,
					cuisine_type,
					address,
					city,
					is_approved
				)
				VALUES (
					'" . $managerId . "',
					'" . $name . "',
					'" . $cuisine . "',
					'" . $address . "',
					'" . $city . "',
					0
				)";

		return $this->db->query($sql);
	}


	public function getAllRestaurants() {

		$sql = "SELECT r.*,
					   u.name AS manager_name,
					   u.email AS manager_email
				FROM restaurants r
				JOIN users u ON r.manager_id = u.id
				ORDER BY r.created_at DESC";

		$result = $this->db->query($sql);

		$restaurants = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$restaurants[] = $row;
			}
		}

		return $restaurants;
	}


	public function getPendingRestaurants() {

		$sql = "SELECT r.*,
					   u.name AS manager_name,
					   u.email AS manager_email
				FROM restaurants r
				JOIN users u ON r.manager_id = u.id
				WHERE r.is_approved = 0
				ORDER BY r.created_at ASC";

		$result = $this->db->query($sql);

		$restaurants = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$restaurants[] = $row;
			}
		}

		return $restaurants;
	}



	public function toggleApproval($id, $status) {

		$sql = "UPDATE restaurants 
				SET is_approved = '" . $status . "' 
				WHERE id = '" . $id . "'";

		return $this->db->query($sql);
	}


	public function deleteRestaurant($id) {

		$sql = "DELETE FROM restaurants 
				WHERE id = '" . $id . "'";

		return $this->db->query($sql);
	}
}

?>
```
