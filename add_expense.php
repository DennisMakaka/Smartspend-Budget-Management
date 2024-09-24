<?php
// Include the database connection file
require_once 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $user_id = $_POST['user_id'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Prepare INSERT statement
    $query = "INSERT INTO expenses (user_id, category_id, amount, date) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    // Check if prepare() succeeded
    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
    }

    // Bind parameters and execute statement
    $stmt->bind_param("iiis", $user_id, $category_id, $amount, $date);
    
    if ($stmt->execute()) {
        echo "Expense added successfully.";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close statement
    $stmt->close();
}

// Close connection (added check for $mysqli existence)
if (isset($mysqli)) {
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Expense</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
    }
    .container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      font-weight: bold;
    }
    .form-group input, .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 14px;
    }
    .form-group button {
      background-color: #3a6da4;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    .form-group button:hover {
      background-color: #1d4e89;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Add Expense</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div class="form-group">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required>
      </div>
      <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
          <!-- PHP code to dynamically populate options -->
          <?php
          // Fetch expense categories from database
          $query = "SELECT category_id, category_name FROM expense_categories";
          $result = $mysqli->query($query);

          // Initialize an array to store category options
          $categoryOptions = array();

          // Loop through result set and populate category options
          while ($row = $result->fetch_assoc()) {
              $categoryOptions[] = '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
          }

          // Output category options dynamically
          foreach ($categoryOptions as $option) {
              echo $option;
          }

          // Close result set
          $result->close();
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" required>
      </div>
      <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
      </div>
      <div class="form-group">
        <button type="submit">Add Expense</button>
      </div>
    </form>
  </div>
</body>
</html>
