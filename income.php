/**
 * income_processor.php
 *
 * This file handles the processing of income submissions from the user.
 *
 * It checks if the user is logged in, establishes a database connection,
 * and processes the income form submission by sanitizing the input data
 * and inserting it into the income database table.
 *
 * If the user is not logged in, they will be redirected to the login page.
 * If the database connection fails, the user will be redirected to an error page.
 * After successfully processing the income submission, the user will be redirected to the home page.
 */

session_start(); // Start the session to manage user authentication
error_reporting(E_ALL); // Enable error reporting for all types of errors

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if the user is not logged in
    exit; // Stop script execution
}

// Database connection settings
$db_host = '127.0.0.1'; // Database host (IP address or localhost)
$db_username = 'root'; // Database username
$db_password = 'SoccerCiTy'; // Database password (update with your actual password)
$db_name = 'smartspenddb'; // Database name
$db_port = 3306; // Database port (default is 3306 for MySQL)

// Create a new MySQLi connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);

// Check the connection status
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log connection error for debugging
    header("Location: error.php"); // Redirect to the error page if the connection fails
    exit; // Stop script execution
}

// Process income form submission if the submit button is clicked
if (isset($_POST['submit'])) {
    $income_type = filter_var($_POST['income_type'], FILTER_SANITIZE_STRING); // Sanitize income type input
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Sanitize amount input
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING); // Sanitize date input

    // Prepare the SQL statement to insert data into the income table
    $stmt = $conn->prepare("INSERT INTO income (UserId, IncomeType, Amount, Date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['user_id'], $income_type, $amount, $date); // Bind parameters for the SQL statement
    $stmt->execute(); // Execute the prepared statement

    // Redirect to the home page after successful submission
    header("Location: home.php");
    exit; // Stop script execution
}

$conn->close(); // Close the database connection


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Tracking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('Background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            position: relative;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
            color: #2575fc;
        }
        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        select, input[type="number"], input[type="date"], input[type="submit"] {
            width: calc(100% - 24px);
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 16px;
        }
        select:focus, input[type="number"]:focus, input[type="date"]:focus {
            border-color: #2575fc;
            outline: none;
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
        }
        input[type="submit"] {
            background-color: #2575fc;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #1d4e89;
        }
        .footer {
            background-color: rgba(58, 109, 164, 0.9); /* Slightly transparent background */
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            border-top: 3px solid #87ceeb;
            position: absolute;
            bottom: 0;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
        }
        .footer .social {
            margin-top: 10px;
        }
        .footer .social a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
        }
        .footer .social a:hover {
            color: #87ceeb;
        }
        @media (max-width: 600px) {
            form {
                padding: 15px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h1><i class="fas fa-wallet"></i> Add Income</h1>
        <label for="income_type">Income Type:</label>
        <select name="income_type" id="income_type" required>
            <option value="">Select income type</option>
            <option value="Salary">Salary</option>
            <option value="Freelance">Freelance</option>
            <option value="Investments">Investments</option>
            <option value="Rent">Rent</option>
            <option value="Dividends">Dividends</option>
            <option value="Interest">Interest</option>
            <option value="Grants">Grants</option>
            <option value="Awards">Awards</option>
            <option value="Selling products">Selling products</option>
            <option value="Selling services">Selling services</option>
            <option value="Royalties">Royalties</option>
            <option value="Commissions">Commissions</option>
            <option value="Part-time job">Part-time job</option>
            <option value="Online sales">Online sales</option>
            <option value="Other">Other</option>
        </select>
        <label for="amount">Amount:</label>
        <input type="number" step="0.01" name="amount" id="amount" required>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>
        <input type="submit" name="submit" value="Add Income">
    </form>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Smartspend Budget Management system.</p>
        <div class="social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</body>
</html>
