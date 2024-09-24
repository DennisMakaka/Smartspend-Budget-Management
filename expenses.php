<?php
session_start();
error_reporting(E_ALL);

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
    die("Connection failed: " . $conn->connect_error);
}

// Process expense form submission
if (isset($_POST['submit'])) {
    $expense_category = filter_var($_POST['expense_category'], FILTER_SANITIZE_STRING);
    $expense_amount = filter_var($_POST['expense_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $expense_date = filter_var($_POST['expense_date'], FILTER_SANITIZE_STRING);
    $expense_description = filter_var($_POST['expense_description'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO expenses (Category, Amount, Date, Description) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("ssss", $expense_category, $expense_amount, $expense_date, $expense_description);
    $stmt->execute();
    $stmt->close();

    // Redirect to home page after successful form submission
    header("Location: expenses.php");
    exit;
}

// Fetch expenses data for chart
$expenses_data = [];
$result = $conn->query("SELECT Category, SUM(Amount) as TotalAmount FROM expenses GROUP BY Category");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expenses_data[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Form and Chart</title>
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
        h1 {
            text-align: center;
            color: #0044cc;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        select, input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #0044cc;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #003399;
        }
        .chart-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
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
        <h1>Expenses Form</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div>
                <label for="expense_category">Category:</label>
                <select id="expense_category" name="expense_category" required>
                    <option value="">Select Category</option>
                    <option value="Housing">Housing</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Food">Food</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Debt Repayment">Debt Repayment</option>
                    <option value="Savings and Investments">Savings and Investments</option>
                    <option value="Personal Care">Personal Care</option>
                    <option value="Entertainment and Recreation">Entertainment and Recreation</option>
                    <option value="Education">Education</option>
                    <option value="Gifts and Donations">Gifts and Donations</option>
                    <option value="Clothing and Accessories">Clothing and Accessories</option>
                    <option value="Childcare and Education">Childcare and Education</option>
                    <option value="Pets">Pets</option>
                    <option value="Miscellaneous">Miscellaneous</option>
                    <option value="Taxes">Taxes</option>
                    <option value="Household Supplies">Household Supplies</option>
                </select>
            </div>
            <div>
                <label for="expense_amount">Amount:</label>
                <input type="number" id="expense_amount" name="expense_amount" required>
            </div>
            <div>
                <label for="expense_date">Date:</label>
                <input type="date" id="expense_date" name="expense_date" required>
            </div>
            <div>
                <label for="expense_description">Description:</label>
                <textarea id="expense_description" name="expense_description" required></textarea>
            </div>
            <div style="text-align: center;">
                <input type="submit" name="submit" value="Add Expense">
            </div>
        </form>
    </div>

    <div class="chart-container">
        <h2>Expense Distribution</h2>
        <canvas id="expense-chart" width="400" height="400"></canvas>
    </div>

    <footer>
        © 2024 SmartSpend Budget Management System.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById('expense-chart').getContext('2d');
            var expenseData = <?php echo json_encode($expenses_data); ?>;
            
            var labels = expenseData.map(function(item) {
                return item.Category;
            });
            
            var data = expenseData.map(function(item) {
                return item.TotalAmount;
            });

            var colors = [
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 99, 132, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(255, 99, 71, 0.5)',
                'rgba(135, 206, 250, 0.5)',
                'rgba(144, 238, 144, 0.5)',
                'rgba(255, 215, 0, 0.5)',
                'rgba(255, 182, 193, 0.5)',
                'rgba(175, 238, 238, 0.5)',
                'rgba(255, 140, 0, 0.5)',
                'rgba(0, 191, 255, 0.5)',
                                'rgba(127, 255, 212, 0.5)',
                'rgba(221, 160, 221, 0.5)',
                'rgba(240, 230, 140, 0.5)',
                'rgba(238, 130, 238, 0.5)',
                'rgba(0, 255, 127, 0.5)',
                'rgba(255, 228, 181, 0.5)'
            ];

            var borderColors = colors.map(function(color) {
                return color.replace('0.5', '1');
            });

            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Expense Distribution',
                        data: data,
                        backgroundColor: colors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Expense Distribution by Category'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.labels[tooltipItem.dataIndex] || '';
                                    var value = data.datasets[0].data[tooltipItem.dataIndex] || 0;
                                    return label + ': KES ' + Number(value).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
