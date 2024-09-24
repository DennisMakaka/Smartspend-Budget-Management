<?php
session_start();
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection settings
$db_host = '127.0.0.1'; // or 'localhost'
$db_username = 'root';
$db_password = 'SoccerCiTy'; // Update with your actual database password
$db_name = 'smartspenddb';
$db_port = 3306; // Update with your actual database port number

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: ". $conn->connect_error);
    header("Location: error.php"); // Redirect to error page
    exit;
}

// Process income form submission
if (isset($_POST['submit'])) {
    $income_type = filter_var($_POST['income_type'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO income (UserId, IncomeType, Amount, Date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['user_id'], $income_type, $amount, $date);
    $stmt->execute();

    // Redirect to home page
    header("Location: home.php");
    exit;
}

?>

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
