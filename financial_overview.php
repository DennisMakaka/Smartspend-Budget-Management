<?php
// Include the database connection file
require_once 'db_connect.php';

// Retrieve income data from income table
$result_income = $conn->query("SELECT * FROM income");
if (!$result_income) {
    echo "Error: " . $conn->error;
    exit;
}
$income_data = array();
while ($row = $result_income->fetch_assoc()) {
    $income_data[] = $row;
}

// Retrieve expense data from expense table
$result_expense = $conn->query("SELECT * FROM expenses");
if (!$result_expense) {
    echo "Error: " . $conn->error;
    exit;
}
$expense_data = array();
while ($row = $result_expense->fetch_assoc()) {
    $expense_data[] = $row;
}

// Initialize variables
$total_income = 0;
$total_expenses = 0;

// Calculate income statistics
foreach ($income_data as $income) {
    $total_income += $income['Amount'];
}
if ($total_income > 0) {
    $average_monthly_income = $total_income / 12;
} else {
    $average_monthly_income = 0;
}

// Calculate expense statistics
foreach ($expense_data as $expense) {
    $total_expenses += $expense['Amount'];
}
if ($total_expenses > 0) {
    $average_monthly_expenses = $total_expenses / 12;
} else {
    $average_monthly_expenses = 0;
}

// Calculate budget statistics
if ($total_income > 0 && $total_expenses > 0) {
    $total_budget = $total_income - $total_expenses;
    $budget_utilization = ($total_expenses / $total_income) * 100;
} else {
    $total_budget = 0;
    $budget_utilization = 0;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
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
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .stats-section {
            margin-bottom: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #0044cc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f0f4f8;
            font-weight: bold;
        }

        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button {
            background-color: #3a6da4;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .button:hover {
            background-color: #1d4e89;
        }

        .print-button {
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="stats-section">
            <h2>Income Statistics</h2>
            <table>
                <tr>
                    <th>Total Income</th>
                    <td>$<?php echo number_format($total_income, 2); ?></td>
                </tr>
                <tr>
                    <th>Average Monthly Income</th>
                    <td>$<?php echo number_format($average_monthly_income, 2); ?></td>
                </tr>
                <!-- Add more income statistics here -->
            </table>
        </div>

        <div class="stats-section">
            <h2>Expense Statistics</h2>
            <table>
                <tr>
                    <th>Total Expenses</th>
                    <td>$<?php echo number_format($total_expenses, 2); ?></td>
                </tr>
                <tr>
                    <th>Average Monthly Expenses</th>
                    <td>$<?php echo number_format($average_monthly_expenses, 2); ?></td>
                </tr>
                <!-- Add more expense statistics here -->
            </table>
        </div>

        <div class="stats-section">
            <h2>Budget Statistics</h2>
            <table>
                <tr>
                    <th>Total Budget</th>
                    <td>$<?php echo number_format($total_budget, 2); ?></td>
                </tr>
                <tr>
                    <th>Budget Utilization</th>
                    <td><?php echo number_format($budget_utilization, 2); ?>%</td>
                </tr>
                <!-- Add more budget statistics here -->
            </table>
        </div>

        <div class="stats-section">
            <h2>Trend Analysis</h2>
            <canvas id="trend-analysis-chart" width="400" height="200"></canvas>
            <!-- Add more trend analysis charts or graphs here -->
        </div>

        <div class="button-container">
            <button id="print-btn" class="print-button" onclick="printPage()">Print Report</button>
        </div>
    </div>

    <footer>
        © 2024 SmartSpend Budget Management System.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize chart using Chart.js
        var ctx = document.getElementById('trend-analysis-chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Income ($)', 'Expenses ($)'],
                datasets: [{
                    label: 'Trend Analysis',
                    data: [<?php echo $total_income; ?>, <?php echo $total_expenses; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)', // Income
                        'rgba(255, 99, 132, 0.5)' // Expenses
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)', // Income
                        'rgba(255, 99, 132, 1)' // Expenses
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Income vs Expenses ($)'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return 'KES ' + Number(tooltipItem.value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                        }
                    }
                }
            }
        });

        // Function to print the page
        function printPage() {
            window.print();
        }
    </script>
</body>
</html>
