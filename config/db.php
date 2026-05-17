<?php

// Database connection

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "food_ordering"
);

// Check connection

if (!$conn) {
    echo "Database connection failed";
}

?>