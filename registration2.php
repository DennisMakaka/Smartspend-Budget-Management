
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
    die("Connection failed: ". $conn->connect_error);
}

// Process registration form submission
if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confirm_password = password_hash($_POST['confirm-password'], PASSWORD_BCRYPT);

    // Check if passwords match
    if (!password_verify($_POST['password'], $confirm_password)) {
        echo "Passwords do not match";
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT UserId FROM user WHERE Email = ?");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Email already exists";
        exit;
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO user (Name, DOB, Gender, Telephone, Email, Password) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("ssssss", $name, $dob, $gender, $telephone, $email, $password);
    $stmt->execute();

    // Redirect to login page
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c8cbde;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="button"] {
            background-color: #007bff;
        }
        button[type="submit"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h1>Sign Up</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <!-- Name -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <!-- Date of Birth -->
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>

        <!-- Gender -->
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
<option value="other">Other</option>
        </select>

        <!-- Telephone -->
        <label for="telephone">Telephone:</label>
        <input type="tel" id="telephone" name="telephone" required>

        <!-- Email Address -->
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <!-- Confirm Password -->
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>

        <!-- Buttons -->
        <button type="submit" name="submit">Submit</button>
        <button type="button" onclick="window.location.href='cancel.html'">Cancel</button>
    </form>
</body>
</html>