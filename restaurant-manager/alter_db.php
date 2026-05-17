<?php
require_once __DIR__ . '/config/db.php';
$db = new Database();
$conn = $db->connect();

$query = "ALTER TABLE menu_items MODIFY image_path LONGTEXT";
if ($conn->query($query) === TRUE) {
    echo "Successfully altered menu_items table.";
} else {
    echo "Error altering table: " . $conn->error;
}
