<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
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
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
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
    <div class="stats-section">
        <h2>Income Statistics</h2>
        <table>
            <tr>
                <th>Total Income</th>
                <td>$<?php echo number_format($mock_income_data['total_income'], 2); ?></td>
            </tr>
            <tr>
                <th>Average Monthly Income</th>
                <td>$<?php echo number_format($mock_income_data['average_monthly_income'], 2); ?></td>
            </tr>
            <!-- Add more income statistics here -->
            <tr>
                <th>Other Income Statistics</th>
                <td><?php echo $mock_income_data['other_income_stat']; ?></td>
            </tr>
        </table>
    </div>

    <div class="stats-section">
        <h2>Expense Statistics</h2>
        <table>
            <tr>
                <th>Total Expenses</th>
                <td>$<?php echo number_format($mock_expense_data['total_expenses'], 2); ?></td>
            </tr>
            <tr>
                <th>Average Monthly Expenses</th>
                <td>$<?php echo number_format($mock_expense_data['average_monthly_expenses'], 2); ?></td>
            </tr>
            <!-- Add more expense statistics here -->
            <tr>
                <th>Other Expense Statistics</th>
                <td><?php echo $mock_expense_data['other_expense_stat']; ?></td>
            </tr>
        </table>
    </div>

    <div class="stats-section">
        <h2>Budget Statistics</h2>
        <table>
            <tr>
                <th>Total Budget</th>
                <td>$<?php echo number_format($mock_budget_data['total_budget'], 2); ?></td>
            </tr>
            <tr>
                <th>Budget Utilization</th>
                <td><?php echo $mock_budget_data['budget_utilization']; ?>%</td>
            </tr>
            <!-- Add more budget statistics here -->
            <tr>
                <th>Other Budget Statistics</th>
                <td><?php echo $mock_budget_data['other_budget_stat']; ?></td>
            </tr>
        </table>
    </div>

    <div class="stats-section">
        <h2>Trend Analysis</h2>
        <canvas id="trend-analysis-chart" width="400" height="200"></canvas>
        <!-- Add more trend analysis charts or graphs here -->
    </div>

    <div class="button-container">
        <button class="button" onclick="location.href='dashboard.php'">Back to Dashboard</button>
    </div>

    <footer>
        <p>&copy; 2024 Smartspend Budget Management System</p>
        <div class="social-container">
            <a href="#" class="social">
                <i class="fab fa-facebook-f fa-fw"></i>
            </a>
            <a href="#" class="social">
                <i class="fab fa-twitter fa-fw"></i>
            </a>
            <a href="#" class="social">
                <i class="fab fa-instagram fa-fw"></i>
            </a>
        </div>
    </footer>

    <!-- Include any JavaScript for charting or additional functionality here -->
    <script>
        // Example: Initialize chart using Chart.js
        var ctx = document.getElementById('trend-analysis-chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Income Trend',
                    data: [4000, 4200, 3800, 4100, 4300, 4500],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>