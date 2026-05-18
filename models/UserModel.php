<?php

require_once __DIR__ . '/../config/database.php';

class UserModel {

	private $db = null;

	function __construct()
	{
		$this->db = getDB();
	}


	public function getAdminByEmail($email) {

		$sql = "SELECT * FROM users 
				WHERE email = '" . $email . "' 
				AND role = 'admin'";

		$result = $this->db->query($sql);

		$admin = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$admin = $row;
			}
		}

		return $admin;
	}

	public function getAdminById($id) {

		$sql = "SELECT * FROM users 
				WHERE id = '" . $id . "' 
				AND role = 'admin'";

		$result = $this->db->query($sql);

		$admin = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$admin = $row;
			}
		}

		return $admin;
	}


	public function checkEmailExists($email) {

		$sql = "SELECT id FROM users 
				WHERE email = '" . $email . "'";

		$result = $this->db->query($sql);

		if ($result->rowCount() > 0) {

			return true;

		} else {

			return false;
		}
	}

	public function createManager($name, $email, $password, $phone) {

		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO users (
					name,
					email,
					password_hash,
					phone,
					role
				)
				VALUES (
					'" . $name . "',
					'" . $email . "',
					'" . $password . "',
					'" . $phone . "',
					'manager'
				)";

		$this->db->query($sql);

		return $this->db->lastInsertId();
	}


	public function createAdmin($name, $email, $password, $phone, $image = null) {

		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO users (
					name,
					email,
					password_hash,
					phone,
					role,
					profile_pic
				)
				VALUES (
					'" . $name . "',
					'" . $email . "',
					'" . $password . "',
					'" . $phone . "',
					'admin',
					'" . $image . "'
				)";

		$this->db->query($sql);

		return $this->db->lastInsertId();
	}


	public function getCustomers() {

		$sql = "SELECT 
					id,
					name,
					email,
					phone,
					is_active,
					created_at
				FROM users
				WHERE role = 'customer'
				ORDER BY created_at DESC";

		$result = $this->db->query($sql);

		$customers_arr = [];

		if ($result->rowCount() > 0) {

			$count = 0;

			while ($row = $result->fetch()) {

				$customer = [];

				$customer['id'] = $row['id'];
				$customer['name'] = $row['name'];
				$customer['email'] = $row['email'];
				$customer['phone'] = $row['phone'];
				$customer['is_active'] = $row['is_active'];
				$customer['created_at'] = $row['created_at'];

				$customers_arr[$count] = $customer;

				$count++;
			}
		}

		return $customers_arr;
	}


	public function toggleUserStatus($id, $status) {

		$sql = "UPDATE users 
				SET is_active = '" . $status . "' 
				WHERE id = '" . $id . "'";

		return $this->db->query($sql);
	}
}

?>