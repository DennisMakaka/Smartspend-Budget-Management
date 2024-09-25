<?php
/**
 * login_processor.php
 *
 * This file handles the user login process, including validating user credentials
 * against the database and managing user sessions upon successful login.
 * 
 * It checks for a valid email and password, and if the credentials are correct,
 * it initializes a session and redirects the user to the home page. If the login 
 * attempt fails, an error message is displayed.
 */

session_start(); // Start the session to manage user login
error_reporting(E_ALL); // Enable error reporting for all types of errors

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
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    header("Location: error.php"); // Redirect to error page
    exit; // Stop further execution
}

// Process login form submission
if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Validate email format
    $password = $_POST['password']; // Get the entered password

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT Password, UserId FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate the user's credentials
    if ($result->num_rows === 0) {
        echo "Invalid email or password"; // Inform the user of invalid credentials
        exit; // Stop further execution
    }

    $user_data = $result->fetch_assoc(); // Fetch user data from the result
    $hashed_password = $user_data['Password']; // Get the hashed password
    $user_id = $user_data['UserId']; // Get the user ID

    // Verify the entered password against the hashed password
    if (password_verify($password, $hashed_password)) {
        // Login successful, start session
        $_SESSION['user_id'] = $user_id; // Store user ID in session
        $_SESSION['email'] = $email; // Store email in session
        header("Location: home.php"); // Redirect to home page
        exit; // Stop further execution
    } else {
        echo "Invalid email or password"; // Inform the user of invalid credentials
        exit; // Stop further execution
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSpend</title>
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="favicon.ico.png" type="image/x-icon">
</head>
<style>
    @import url("https://use.fontawesome.com/releases/v6.5.1/css/all.css");
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
* {
    margin: 0;
    padding: 0;
}
.icon{
    border-radius:  2px;
    width: 30px;
}
.main {
    width: 100%;
    background: linear-gradient(to top, rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%), url(Background.jpg);
    background-position: center;
    background-size: cover; 
    height: 100vh;
}

.navbar{
    width: 1200px;
    height:75px;
    margin: auto;
}

.icon {
    width: 200px;
    float: left;
    height: 70px;
}

.logo {
    color: #f2b678;
    font-size: 35px;
    font-family: 'Times New Roman', Times, serif;
    padding-left: 20px;
    float: left;
    padding-top: 10px;    
}

.menu {
    width: 400px;
    float: left;
    height: 70px;
}

ul {
    float: left;
    display: flex;
    justify-content: center;
    align-items: center;
}

ul li {
    list-style: none;
    margin-left: 62px;
    margin-top: 27px;
    font-size: 14px;
     font-family: 'Times New Roman', Times, serif;
}

ul li a {
    text-decoration: none;
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    transition:0.4s ease-in-out;
}  

ul li a:hover {
    color: #f2b678;
}

.search {
    width: 330px;
    float: left;
    margin-left: 270px;
}
.srch {
    font-family: 'Times New Roman';
    width: 200px;
    height: 80px;
    background: transparent;
    border: 1px solid #c07f3f;
    margin-top: 13px;
    color: beige;
    border-right: none;
    font-size: 16px;
    float: left;
    padding: 10px;
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
}
 .btn {
    width: 100px;
    height: 40px;
    background: #f4c198;
    border: 2px solid #f4c198;
    margin-top: 13px;
    color: aliceblue;
    font-size: 15px;
    border-bottom-right-radius: 5px;
    border-bottom-right-radius: 5px;
 }
 .btn:focus {
    outline: none;
}

.srch:focus {
    outline: none;
}

.content {
    width: 1200px;
    height: auto;
    margin: auto;
    color: azure;
    position: relative;
}

.content .par {
    padding-left: 20px;
    padding-bottom:25px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    letter-spacing: 1.2px;
    line-height: 30px;
}

.content h1 {
     font-family: 'Times New Roman';
     font-size: 50px;
     padding-left: 20px;
     margin-top: 9%;
     letter-spacing: 2px;
}
.content h2 {
     font-family: 'Times New Roman';
     font-size: 30px;
     padding-left: 20px;
     margin-top: 1%;
     letter-spacing: 1px;
     color:gainsboro;
}

.content  .cn {
    width: 160px;
    height: 40px;
    background: #ff7200;
    border: none;
    margin-bottom: 10px;
    margin-left: 20px;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
}

.content .cn a {
    text-decoration: none;
    color: black;
    transition: 0.3s ease;
     }
    
.cn:hover {
    background-color: whitesmoke;
}

.content span {
    color: #ff7200;
    font-size: 40px;
}

.form {
    width: 250px;
    height: 380px;
    background: linear-gradient(to top, rgba(0,0,0,0.8)50%, rgba(0,0,0,0.8)50%);
    position: absolute;
    top: -20px;
    left: 870px;
    border-radius: 10px;
    padding: 25px;    
}

.form h2 {
    width: 220px;
    font-family: sans-serif;
    text-align: center;
    color: #ff7200;
    font-size: 22px;
    background-color: azure;
    border-radius: 10px;
    margin:2px;
    padding: 8px;
}

.form input {
    width: 240px;
    height:  36px;
    background: transparent;
    border-bottom: 1px solid #ff7200;
    border-top: none;
    border-left: none;
    border-right: none; 
    color: azure;
    font-size: 15px;
    letter-spacing: 1px;
    margin-top: 30px;
    font-family: sans-serif;
}

.form input:focus {
    outline: none;
}

::placeholder{
    color: azure;
    font-size: 15px;
    letter-spacing: 1px;
    font-family: sans-serif;
}

.btnn {
    width: 240px;
 40px;
    background: #ff7200;
    border: none ;
    margin-top: 30px;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
    color: azure;
    font-family: sans-serif;
}

.btnn:hover {
    background-color: aliceblue;
    color: #ff7200;
}

.btnn a {
    text-decoration: none;
    color:black;
    transition: 0.3s ease;
    font-weight: bold;
    }

.form .link {
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    font-size: 17px;
    padding-top: 20px;
    text-align: center;
    }

.form .link a {
    text-decoration: none;
    color: #ff7200;
}

.liw {
    padding-top: 15px;
    padding-bottom: 10px;
    text-align: center;
}

.icon a {
    text-decoration: none;
    color: white;

}

.social-container {
    margin-top: 20px; /* Adjust margin as needed */
    display: flex;
    justify-content: center; /* Center the social icons horizontally */
}

.social {
    margin: 0 10px; /* Adjust horizontal spacing between social icons */
    font-size: 20px; /* Adjust the size of the social icons */
    color: #333; /* Adjust the color of the social icons */
    text-decoration: none;
}

/* Add hover effect to social icons */
.social:hover {
    color: #007bff; /* Change color on hover */
}


</style>

<body>
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">SmartSpend Budget Management System</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="homee.html">Home</a></li>
                    <li><a href="about_us.html">About</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li> 
                </ul>
            </div>
            <div class="search">
                <input type="text" placeholder="Type here">
                <button class="btn">Search</button>
            </div>
        </div>
        <div class="content">
            <h1>Welcome to SmartSpend!<br></h1> <h2><span>Solution for effortless expense management!</span></h2>
            <p class="par">Tired of struggling to keep track of your expenses? Say goodbye to financial stress with SmartSpend <br>Budget Management System, the innovative system designed to revolutionize the way you manage <br>your money. <br>Effortlessly track your daily expenditures, set reminders for upcoming payments and gain valuable <br>insights into your spending habits - all in one convenient, easy-to-use platform. From budgeting <br>tools to personalized analytics, we've got everything you need to take control of your finances and <br>achieve your financial goals. <br>Experience the power of financial empowerment at your fingertips. Join thousands of satisfied users <br>who are already on their journey to financial freedom with SmartSpend.</p>

            <button class="cn"> <a href="registration2.php">JOIN US</a> </button>

            <div class="form">
                <h2>Login Here!</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="text" placeholder="Email" name="email" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <button class="btnn" type="submit" name="login">Login</button>

                    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

                    <p class="link">Don't have an account? <br>
                    <a href="registration2.php">Sign Up</a> here</p>
                    <p class="liw">Login with:</p>

                    <div class="social-container">
                        <a href="https://accounts.google.com/v3/signin/identifier?authuser=0&continue=https%3A%2F%2Fmail.google.com%2Fmail&ec=GAlAFw&hl=en&service=mail&flowName=GlifWebSignIn&flowEntry=AddSession&dsh=S1091072858%3A1709194773885046&theme=glif" class="social" target="_self"><i class="fa-brands fa-google"></i></a>
                        <a href="https://www.facebook.com/" class="social" target="_self"><i class="fa-brands fa-facebook"></i></a>
                        <a href="https://twitter.com/i/flow/login" class="social" target="_self"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://www.instagram.com/accounts/login/?hl=en" class="social" target="_self"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/login" class="social" target="_self"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>