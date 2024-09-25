<?php
/**
 * session_manager.php
 *
 * This file handles user session management, including checking if a user is logged in
 * and providing functionality for logging out.
 * 
 * If a user is not logged in, they will be redirected to the login page.
 * If the logout request is made, the session will be destroyed and the user will 
 * be redirected to the login page.
 */

// Start a session to manage user authentication
session_start();

// Check if the user is logged in by verifying the presence of user_id and email in the session
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php"); 
    exit; // Ensure no further code is executed after the redirect
}

// Logout functionality: checks if the logout button was pressed
if (isset($_POST['logout'])) {
    // Destroy the session to log the user out
    session_destroy();
    // Redirect to the login page after logging out
    header("Location: login.php");
    exit; // Ensure no further code is executed after the redirect
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f4f8;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      background-color: #3a6da4;
      color: #fff;
      padding: 20px;
      text-align: center;
      position: relative;
      border-bottom: 3px solid #87ceeb;
    }
    header h1 {
      font-size: 28px;
      margin: 0;
    }
    header .profile-icon {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #fff;
    }
    header .profile-icon i {
      margin-right: 5px;
    }
    header .notification-icon {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #fff;
    }
    header .notification-icon .badge {
      position: absolute;
      top: -10px;
      right: -10px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 14px;
    }
    .notification-dropdown {
      display: none;
      position: absolute;
      top: 60px;
      right: 20px;
      background-color: #fff;
      border: 1px solid #ddd;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      width: 300px;
      z-index: 1000;
      padding: 10px;
    }
    .notification-dropdown.active {
      display: block;
    }
    .notification-dropdown h4 {
      margin: 0;
      font-size: 16px;
    }
    .notification-dropdown p {
      margin: 0;
      font-size: 14px;
      color: #555;
    }
    .notification-dropdown .notification {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }
    .notification-dropdown .notification:last-child {
      border-bottom: none;
    }
    nav {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 20px;
    }
    nav a {
      color: #3a6da4;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: color 0.2s ease;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      width: 150px;
      height: 150px;
    }
    nav a:hover {
      color: #1d4e89;
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }
    nav i {
      font-size: 64px;
      margin-bottom: 10px;
    }
    nav span {
      font-size: 18px;
      font-weight: 600;
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
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .button:hover {
      background-color: #1d4e89;
    }
    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
    .social-container {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .social {
      color: #3a6da4;
      text-decoration: none;
      display: flex;
      align-items: center;
      transition: color 0.2s ease;
      margin: 0 5px;
    }
    .social:hover {
      color: #1d4e89;
    }
    .logout-button {
      position: absolute;
      bottom: 20px;
      right: 20px;
      background-color: #3a6da4;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .logout-button:hover {
      background-color: #1d4e89;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script>
    // Function to fetch and display reminders
    function fetchReminders() {
        fetch('fetch_reminders.php')
            .then(response => response.json())
            .then(data => {
                let notificationsDiv = document.getElementById('notificationDropdown');
                let notificationIcon = document.querySelector('.notification-icon .badge');
                notificationsDiv.innerHTML = ''; // Clear existing notifications

                if (data.length > 0) {
                    notificationIcon.textContent = data.length;
                    data.forEach(reminder => {
                        let notification = document.createElement('div');
                        notification.className = 'notification';
                        
                        let title = document.createElement('h4');
                        title.textContent = 'Upcoming Payment Reminder';
                        
                        let description = document.createElement('p');
                        description.textContent = reminder.description;

                        let datetime = document.createElement('p');
                        datetime.textContent = 'Due on: ' + new Date(reminder.payment_date).toLocaleDateString();

                        notification.appendChild(title);
                        notification.appendChild(description);
                        notification.appendChild(datetime);

                        notificationsDiv.appendChild(notification);
                    });
                } else {
                    notificationIcon.textContent = '0';
                    let noReminders = document.createElement('p');
                    noReminders.textContent = 'No upcoming reminders.';
                    notificationsDiv.appendChild(noReminders);
                }
            })
            .catch(error => console.error('Error fetching reminders:', error));
    }

    // Toggle notification dropdown
    function toggleNotifications() {
        document.getElementById('notificationDropdown').classList.toggle('active');
    }

    // Fetch reminders on page load
    window.onload = fetchReminders;
  </script>
</head>
<body>
  <header>
    <h1>Dashboard</h1>
    <form method="post" action="">
        <button type="submit" name="logout" class="logout-button">
            Logout
        </button>
    </form>
    <div class="profile-icon">
      <i class="fas fa-user"></i> <!-- Replace with appropriate icon class -->
      <span><?php echo $_SESSION['email']; ?></span>
    </div>
    <div class="notification-icon" onclick="toggleNotifications()">
      <i class="fas fa-bell"></i>
      <span class="badge">0</span>
    </div>
  </header>
  <div class="notification-dropdown" id="notificationDropdown">
    <!-- Notifications will be populated here -->
  </div>
  <nav>
    <a href="income.php">
      <i class="fas fa-money-bill-wave fa-fw"></i>
      <span>Income</span>
    </a>
    <a href="expenses.php">
      <i class="fas fa-credit-card fa-fw"></i>
      <span>Expenses</span>
    </a>
    <a href="payment.php">
      <i class="fas fa-calendar-check fa-fw"></i>
      <span>Schedule Payment</span>
    </a>
    <a href="financial_overview.php">
      <i class="fas fa-chart-line fa-fw"></i>
      <span>Statistics</span>
    </a>
  </nav>
  <div class="button-container">
    <button class="button" onclick="location.href='upcoming_payments.php'">
      <i class="fas fa-clock fa-fw"></i>
      View Upcoming Payments
    </button>
    <button class="button" onclick="location.href='payment.php'">
      <i class="fas fa-plus-circle fa-fw"></i>
      Add New Payment
    </button>
    <button class="button" onclick="location.href='upcoming_payments.php'">
      <i class="fas fa-list-ul fa-fw"></i>
      View All Payments
    </button>
    <button class="button" onclick="location.href='process_reminder.php'">
      <i class="fas fa-bell fa-fw"></i>
      Set Reminders
    </button>
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
</body>
</html>
