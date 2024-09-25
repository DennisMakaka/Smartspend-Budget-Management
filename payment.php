<?php
/**
 * payment_processor.php
 *
 * This file processes the submission of a payment form. It validates the input data,
 * sanitizes it, and inserts the payment details into the payment_schedule database table.
 * 
 * If the required fields are not filled out correctly, an error message is displayed.
 * Upon successful insertion of the data, the user is redirected to the home page.
 */

include 'db_connect.php'; // Include database connection file

// Check if the request method is POST and the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : ''; // Trim whitespace from category name
    $payment_amount = isset($_POST['payment_amount']) ? floatval($_POST['payment_amount']) : 0; // Convert payment amount to float
    $payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : ''; // Get payment date
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : ''; // Trim whitespace from payment method
    $description = isset($_POST['description']) ? trim($_POST['description']) : ''; // Trim whitespace from description

    // Validate input formats (you may need to adjust based on your requirements)
    if (empty($category_name) || $payment_amount <= 0 || empty($payment_date) || empty($payment_method)) {
        echo "Please fill in all required fields."; // Notify user of missing fields
        exit; // Stop further execution
    }

    // Prepare SQL statement to insert data into payment_schedule table
    $sql = "INSERT INTO payment_schedule (user_id, category_id, payment_date, payment_amount, payment_method, description) VALUES (?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error; // Log error if statement preparation fails
        exit; // Stop further execution
    }

    // For demo purposes, let's assume user_id and category_id are static values
    $user_id = 1; // Replace with actual user_id from your session or form data
    $category_id = 1; // Replace with actual category_id from your session or form data

    // Bind parameters to the prepared statement
    $stmt->bind_param("iissds", $user_id, $category_id, $payment_date, $payment_amount, $payment_method, $description);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect to success page or back to the form page
        header("Location: home.php"); // Redirect to home page on success
        exit; // Stop further execution
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Display error message on failure
    }

    $stmt->close(); // Close the statement
    $conn->close(); // Close the database connection
} else {
    echo "Fill in all the fields"; // Prompt user to fill in all fields if form not submitted correctly
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Schedule Payment</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: url('Background.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    h2 {
        text-align: center;
        color: #0044cc;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select,
    textarea,
    button {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        margin-bottom: 15px;
        font-size: 16px;
    }

    button {
        background-color: #0044cc;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        padding: 15px;
        transition: background-color 0.2s ease;
    }

    button:hover {
        background-color: #003d99;
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        position: fixed;
        width: 100%;
        bottom: 0;
        left: 0;
    }
</style>
</head>
<body>
<div class="container">
    <form id="schedule-payment-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <h2>Schedule Payment</h2>

    <label for="category">Category Name:</label>
    <input type="text" id="category" name="category_name" required>

    <label for="payment-amount">Payment Amount:</label>
    <input type="number" id="payment-amount" name="payment_amount" required min="0" step="0.01">

    <label for="payment-date">Payment Date:</label>
    <input type="date" id="payment-date" name="payment_date" required>

    <label for="payment-method">Payment Method:</label>
    <select id="payment-method" name="payment_method" required>
        <option value="">Select Payment Method</option>
        <option value="Credit Card">Credit Card</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cash">Cash</option>
        <option value="M-pesa">M-pesa</option>
    </select>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" placeholder="Optional description..."></textarea>

    <button type="submit" name="submit">Schedule Payment</button>
</form>

</div>

<footer>
    © 2024 SmartSpend Budget Management System.
</footer>
</body>
</html>