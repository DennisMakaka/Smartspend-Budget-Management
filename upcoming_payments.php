<?php
include 'db_connect.php'; // Include database connection

session_start();
error_reporting(E_ALL);

// Fetch upcoming payments
$sql = "SELECT payment_amount, payment_date, payment_method, description 
        FROM payment_schedule 
        WHERE payment_date >= CURDATE() 
        ORDER BY payment_date ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$stmt->execute();
$result = $stmt->get_result();

$upcoming_payments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $upcoming_payments[] = $row;
    }
}

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Payments</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('Background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payments-container {
            width: 90%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #0044cc;
            text-align: center;
            margin-bottom: 20px;
            font-size: 32px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        .payment {
            padding: 20px;
            margin-bottom: 15px;
            background-color: #fff;
            border-left: 4px solid #0044cc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .payment:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        .payment h2 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #333;
        }
        .payment p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .payment-amount {
            font-weight: bold;
            color: #27ae60;
            font-size: 18px;
        }
        .no-payments {
            text-align: center;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            color: #7f8c8d;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 0;
            font-size: 14px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payments-container">
            <h1>Upcoming Payments</h1>
            <?php if (!empty($upcoming_payments)) : ?>
                <?php foreach ($upcoming_payments as $payment) : ?>
                    <div class="payment">
                        <h2><?php echo htmlspecialchars($payment['description']); ?></h2>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($payment['payment_date']); ?></p>
                        <p><strong>Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
                        <p class="payment-amount"><strong>Amount:</strong> $<?php echo number_format($payment['payment_amount'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="no-payments">
                    No upcoming payments scheduled.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        © 2024 SmartSpend Budget Management System. All rights reserved.
    </footer>
</body>
</html>

