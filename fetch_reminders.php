<?php

// Include the database connection file
include 'db_connect.php';

// Get user-specific notifications
session_start(); // Start the session to access user_id

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT * FROM reminders WHERE user_id = $user_id AND reminder_status = 'sent' ORDER BY reminder_date DESC, reminder_time DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($reminder = $result->fetch_assoc()) {
            // Display each reminder
            echo "Reminder: " . $reminder['reminder_message'] . "<br>";
        }
    } else {
        echo "No new reminders";
    }
} else {
    echo "User not logged in";
}

// Close the database connection
$conn->close();

?>




