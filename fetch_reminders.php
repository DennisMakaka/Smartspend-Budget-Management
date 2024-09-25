<?php
/**
 * @file
 * Fetches and displays user-specific reminders from the database.
 * Requires the user to be logged in.
 * 
 * @author Dennis Makaka
 */

// Include the database connection file
include 'db_connect.php';

// Start the session to access user information
session_start();

// Check if the user is logged in by verifying session variable
if (isset($_SESSION['user_id'])) {
    // Get the user ID from session
    $user_id = $_SESSION['user_id'];
    
    // SQL query to fetch reminders for the logged-in user that have a status of 'sent'
    $sql = "SELECT * FROM reminders WHERE user_id = $user_id AND reminder_status = 'sent' ORDER BY reminder_date DESC, reminder_time DESC";
    
    // Execute the query and get the result
    $result = $conn->query($sql);

    // Check if there are any reminders for the user
    if ($result->num_rows > 0) {
        // Loop through each reminder and display the message
        while ($reminder = $result->fetch_assoc()) {
            echo "Reminder: " . $reminder['reminder_message'] . "<br>";
        }
    } else {
        // If no reminders are found, display a message
        echo "No new reminders";
    }
} else {
    // If the user is not logged in, display an error message
    echo "User not logged in";
}

// Close the database connection
$conn->close();
?>
