<?php
/**
 * Database configuration script
 *
 * This script establishes a connection to the MySQL database using MySQLi.
 * It defines the necessary credentials and checks for a successful connection.
 * If the connection fails, an error message is displayed.
 * 
 * PHP version 7.4
 * 
 * @category Database
 * @package  Database_Connection
 * @author   Dennis Makaka
 * @license  MIT License
 * @link     http://yourwebsite.com
 */

// Database configuration
$db_host = 'localhost'; // Hostname where the database server is located
$db_username = 'root';  // Username to connect to the database
$db_password = 'SoccerCiTy';  // Password associated with the database username
$db_name = 'smartspenddb';  // Name of the database to connect to
$db_port = 3306;  // Port number for MySQL (default is 3306, can be modified if the server uses a different port)

// Create a new mysqli object for database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);

// Check if the connection was successful
if ($conn->connect_error) {
    // If there is a connection error, terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}

// If the connection is successful, you can proceed to execute SQL queries or other database operations.
// Remember to close the connection when done, usually at the end of the script or when it's no longer needed.
?>
