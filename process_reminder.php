<?php
/**
 * reminder_sender.php
 *
 * This script connects to the database and retrieves pending payment reminders for the current date.
 * It sends email notifications to users about their due payments and updates the reminder status
 * in the database to indicate that the reminder has been sent.
 * 
 * Functions included:
 * - sendReminders: Retrieves pending reminders and sends email notifications.
 * - getPaymentInfo: Retrieves payment details based on the payment ID.
 * - getUserEmail: Retrieves the user's email address based on the user ID.
 */

// Establish database connection
$conn = mysqli_connect("localhost", "root", "SoccerCiTy", "smartspenddb");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to send reminders
function sendReminders($conn) {
    // Get today's date and time
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');

    // Retrieve pending reminders for today
    $sql = "SELECT * FROM reminders WHERE reminder_date = '$current_date' AND reminder_time <= '$current_time' AND reminder_status = 'pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($reminder = $result->fetch_assoc()) {
            // Send reminder notification (e.g., email, in-app notification, etc.)
            // For demo purposes, let's assume we're sending an email
            $payment_id = $reminder['payment_id'];
            $payment_info = getPaymentInfo($payment_id, $conn);
            $user_email = getUserEmail($payment_info['user_id'], $conn);
            $subject = "Payment Reminder: " . $payment_info['category_name'];
            $message = "Dear " . $payment_info['user_name'] . ",\n\nThis is a reminder that your payment for " . $payment_info['category_name'] . " is due today.\n\nBest regards,\nSmartSpend Budget Management System.";
            mail($user_email, $subject, $message);

            // Update reminder status to 'sent'
            $sql = "UPDATE reminders SET reminder_status = 'sent' WHERE id = " . $reminder['id'];
            $conn->query($sql);
        }
    }
}

// Function to get payment information
function getPaymentInfo($payment_id, $conn) {
    $sql = "SELECT * FROM payment_schedule WHERE id = " . $payment_id;
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Function to get user email
function getUserEmail($user_id, $conn) {
    $sql = "SELECT email FROM users WHERE id = " . $user_id;
    $result = $conn->query($sql);
    return $result->fetch_assoc()['email'];
}

// Call the sendReminders function
sendReminders($conn);

// Close database connection
$conn->close();
?>


<!-- reminder_setting.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Payment Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('Background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reminder-setting {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        .reminder-setting h2 {
            color: #0044cc;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .reminder-setting form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .reminder-setting label {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        .reminder-setting input[type="date"],
        .reminder-setting input[type="time"],
        .reminder-setting select,
        .reminder-setting button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .reminder-setting button {
            background-color: #0044cc;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 12px;
            transition: background-color 0.3s ease;
        }

        .reminder-setting button:hover {
            background-color: #003399;
        }

        @media (max-width: 500px) {
            .reminder-setting {
                padding: 20px;
            }

            .reminder-setting h2 {
                font-size: 20px;
            }

            .reminder-setting button {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="reminder-setting">
        <h2>Set Reminder</h2>
        <form action="process_reminder.php" method="post">
            <label for="reminder-date">Reminder Date:</label>
            <input type="date" id="reminder-date" name="reminder_date" value="<?php echo date('Y-m-d'); ?>">

            <label for="reminder-time">Reminder Time:</label>
            <input type="time" id="reminder-time" name="reminder_time" value="<?php echo date('H:i'); ?>">

            <label for="reminder-frequency">Reminder Frequency:</label>
            <select id="reminder-frequency" name="reminder_frequency">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>

            <!-- Assuming payment_id is fetched from session or passed as parameter -->
            <input type="hidden" name="payment_id" value="<?php echo $payment_id; ?>">

            <button type="submit">Set Reminder</button>
        </form>
    </div>
</body>
</html>

