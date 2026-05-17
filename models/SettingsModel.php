<?php

require_once __DIR__ . '/../config/database.php';

class SettingsModel {

	private $db = null;

	function __construct()
	{
		$this->db = getDB();
	}


	public function getSettings() {

		$sql = "SELECT setting_key, setting_value 
				FROM platform_settings";

		$result = $this->db->query($sql);

		$settings = [];

		if ($result->rowCount() > 0) {

			while ($row = $result->fetch()) {

				$settings[$row['setting_key']] = $row['setting_value'];
			}
		}

		return $settings;
	}


	public function updateSettings($commission, $baseFee, $feePerKm, $categories) {


		$sql = "UPDATE platform_settings
				SET setting_value = '" . $commission . "'
				WHERE setting_key = 'commission_rate_pct'";

		$this->db->query($sql);



		$sql = "UPDATE platform_settings
				SET setting_value = '" . $baseFee . "'
				WHERE setting_key = 'base_delivery_fee'";

		$this->db->query($sql);


		$sql = "UPDATE platform_settings
				SET setting_value = '" . $feePerKm . "'
				WHERE setting_key = 'delivery_fee_per_km'";

		$this->db->query($sql);



		if ($categories != null) {

			$json = json_encode($categories);

			$sql = "UPDATE platform_settings
					SET setting_value = '" . $json . "'
					WHERE setting_key = 'cuisine_categories'";

			$this->db->query($sql);
		}

		return true;
	}
}

?>