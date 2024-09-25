<?php
// Starting the session
session_start();

// Suppressing error reporting
error_reporting(0);

// Including configuration file for database connection
include('includes/config.php');

// Checking if session login is set, if yes, resetting it
if ($_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

// If the login form is submitted
if (isset($_POST['login'])) {
  
    // Retrieving email and password from the form
    $email = $_POST['emailid'];
    $password = md5($_POST['password']); // Hashing the password using md5

    // Preparing SQL query to verify login credentials
    $sql = "SELECT EmailId,Password,StudentId,Status FROM tblstudents WHERE EmailId=:email and Password=:password";
    $query = $dbh->prepare($sql);
    
    // Binding parameters for the SQL query
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    
    // Executing the query
    $query->execute();
    
    // Fetching the query results
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // Checking if any record matches the provided credentials
    if ($query->rowCount() > 0) {
        // Iterating through the results
        foreach ($results as $result) {
            // Storing student ID in the session
            $_SESSION['stdid'] = $result->StudentId;
            
            // Checking if the account is active (status = 1)
            if ($result->Status == 1) {
                // Setting session login and redirecting to dashboard
                $_SESSION['login'] = $_POST['emailid'];
                echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
            } else {
                // If account is blocked, showing alert
                echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
            }
        }
    } else {
        // Showing alert if credentials are invalid
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags for character set, viewport, and compatibility -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>About Us | SmartSpend</title>
  
  <!-- Internal CSS for styling -->
  <style>
    /* General element styling */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* Body styling */
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: #f0f0f0;
    }

    /* About page container styling */
    .about-page {
      width: 80%;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* About section styling */
    .about-section {
      padding: 50px;
      text-align: center;
      color: white;
      background-image: url(Background.jpg);
      background-size: cover;
    }

    /* Features section styling */
    .features-section {
      padding: 50px;
      text-align: center;
      color: #333;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .features-section h2 {
      width: 100%;
    }

    .features-section ul {
      list-style: none;
    }

    .features-section li {
      margin: 10px;
    }

    /* Mission section styling */
    .mission-section {
      padding: 50px;
      text-align: center;
      color: white;
      background-color: #0056b3;
    }

    /* Contact section styling */
    .contact-section {
      padding: 50px;
      text-align: center;
      color: #333;
    }

    .contact-section a {
      text-decoration: none;
      color: #333;
    }

    /* Button styling */
    .btn {
      background-color: red;
      color: white;
      border-radius: 5px;
      padding: 10px 20px;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    /* Responsive design for smaller screens */
    @media screen and (max-width: 768px) {
      .about-page {
        width: 100%;
      }
    }

    @media screen and (max-width: 1024px) {
      .about-page {
        width: 100%;
      }
    }

    ul li {
      list-style-type: none;
    }
  </style>
</head>
<body>
  <!-- About page content -->
  <div class="about-page">
    <!-- About section -->
    <div class="about-section">
      <h1>About Us</h1>
      <p>We are SmartSpend, a budget management system that helps you track your daily expenditure easily and efficiently. We are a team of passionate developers who believe that everyone deserves to have a clear and accurate picture of their financial situation.</p>
    </div>

    <!-- Features section -->
    <div class="features-section">
      <h2>What We Do</h2>
      <p>We provide you with a simple and user-friendly application that allows you to:</p>
      <ul>
        <li>Create an account and log into the system securely.</li>
        <li>Create reminders for scheduled payments, such as bills, rent, or subscriptions.</li>
        <li>View a pie chart for your daily expenses, categorized by type, such as food, transportation, or entertainment.</li>
        <li>Save your expenses records for future reference and analysis.</li>
      </ul>
    </div>

    <!-- Mission section -->
    <div class="mission-section">
      <h2>Why We Do It</h2>
      <p>We understand that managing your budget can be challenging and stressful, especially if you don't have the right tools or habits. That's why we created SmartSpend, a budget management system that aims to solve the common problems that people face when it comes to their finances, such as:</p>
      <ul>
        <li>Inability to keep records of their expenses.</li>
        <li>Difficulty in remembering scheduled payments.</li>
        <li>Lack of awareness of their spending patterns and habits.</li>
        <li>Difficulty in saving money or achieving their financial goals.</li>
      </ul>
      <p>With SmartSpend, you can easily and efficiently track your daily expenditure, monitor your cash flow, and plan your budget accordingly. You can also set your own spending limits, goals, and alerts to help you stay on track and avoid overspending.</p>
    </div>

    <!-- Contact section -->
    <div class="contact-section">
      <h2>How to Contact Us</h2>
      <p>If you are interested in using our budget management system, you can download our application from www.smartspend.com. You can also visit our website to learn more about our features and benefits.</p> <br><br>
      <p>If you have any questions, feedback, or suggestions, you can contact us by email at [devmakaka@gmail.com] or by phone at +254 799066347. We would love to hear from you and help you with any issues or concerns you may have.</p> <br><br>
      <p>Thank you for choosing SmartSpend, the smart way to manage your budget. 😊</p>
    </div>
  </div>
</body>
</html>
