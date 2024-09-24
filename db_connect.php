<?php
$db_host = 'localhost'; // Hostname
$db_username = 'root';  // Username
$db_password = 'SoccerCiTy';  // Password
$db_name = 'smartspenddb';  // Database name
$db_port = 3306;  // Port (if different from default)

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

