<?php
// Returns a mysqli connection — used by all models/controllers
function getDB() {
    $conn = new mysqli('localhost', 'root', '', 'online_food_ordering_system');
    if ($conn->connect_error) {
        die("DB connection failed: " . $conn->connect_error);
    }
    $conn->set_charset('utf8');
    return $conn;
}
?>
