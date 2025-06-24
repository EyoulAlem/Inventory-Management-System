<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8mb4");
?>
