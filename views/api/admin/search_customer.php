<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

	include_once("../../../config/database.php");

	if (!isset($_SESSION['admin_id'])) {
		$response = [
			"error" => "Unauthorized"
		];

		echo json_encode($response);
		exit;
	}

	$query = $_GET['q'] ?? '';
	$query = trim($query);

	if (strlen($query) < 2) {

		$response = [
			"customers" => []
		];

		echo json_encode($response);
		exit;
	}

	$db = getDB();

	$safe = "%" . $query . "%";

	$sql = "SELECT id, name, email, phone, is_active 
			FROM users 
			WHERE role = 'customer'
			AND (
				name LIKE ? 
				OR email LIKE ? 
				OR phone LIKE ?
			)
			ORDER BY name ASC
			LIMIT 20";

	$stmt = $db->prepare($sql);

	$stmt->execute([$safe, $safe, $safe]);

	$result = $stmt->fetchAll();

	$customers_arr = [];

	if (!empty($result)) {

		$count = 0;

		foreach ($result as $row) {

			$customer = [];

			$customer['id'] = $row['id'];
			$customer['name'] = $row['name'];
			$customer['email'] = $row['email'];
			$customer['phone'] = $row['phone'];
			$customer['is_active'] = $row['is_active'];

			$customers_arr[$count] = $customer;

			$count++;
		}
	}

	$response = [
		"customers" => $customers_arr
	];

	echo json_encode($response);
}
?>