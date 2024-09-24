-- Active: 1719221594415@@127.0.0.1@3306@smartspenddb
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION['login']!=''){
$_SESSION['login']='';
}
if(isset($_POST['login']))
{
  
$email=$_POST['emailid'];
$password=md5($_POST['password']);
$sql ="SELECT EmailId,Password,StudentId,Status FROM tblstudents WHERE EmailId=:email and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
 foreach ($results as $result) {
 $_SESSION['stdid']=$result->StudentId;
if($result->Status==1)
{
$_SESSION['login']=$_POST['emailid'];
echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else {
echo "<script>alert('Your Account Has been blocked .Please contact admin');</script>";

}
}

} 

else{
echo "<script>alert('Invalid Details');</script>";
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>About Us | SmartSpend</title>
  <!-- Here is where you write your internal CSS styles -->
  <style>
    /* You can use a * selector to apply styles to all elements */
    * {
      /* You can use a box-sizing property to define how the width and height of an element are calculated */
      box-sizing: border-box;
      /* You can use a margin property to set the margin area outside the element */
      margin: 0;
      /* You can use a padding property to set the padding area inside the element */
      padding: 0;
    }

    /* You can use an element selector to apply styles to a specific HTML element */
    body {
      /* You can use a font-family property to set the font of the text */
      font-family: Arial, Helvetica, sans-serif;
      /* You can use a background-color property to set the background color of the element */
      background-color: #f0f0f0;
    }

    /* You can use a class selector to apply styles to a specific class of elements */
    .about-page {
      /* You can use a width property to set the width of the element */
      width: 80%;
      /* You can use a max-width property to set the maximum width of the element */
      max-width: 1200px;
      /* You can use a margin property with auto value to center the element horizontally */
      margin: 0 auto;
    }

    .about-section {
      /* You can use a padding property to set the space around the content of the element */
      padding: 50px;
      /* You can use a text-align property to set the alignment of the text */
      text-align: center;
      /* You can use a color property to set the color of the text */
      color: white;
      /* You can use a background-image property to set the background image of the element */
      background-image: url(Background.jpg);
      /* You can use a background-size property to set the size of the background image */
      background-size: cover;
    }

    .features-section {
      padding: 50px;
      text-align: center;
      color: #333;
      /* You can use a display property to set how the element is displayed */
      display: flex;
      /* You can use a flex-wrap property to set how the flex items are wrapped */
      flex-wrap: wrap;
      /* You can use a justify-content property to set how the flex items are aligned along the main axis */
      justify-content: center;
    }

    .features-section h2 {
      /* You can use a width property with 100% value to make the element span the entire width of the parent element */
      width: 100%;
    }

    .features-section ul {
      /* You can use a list-style property to set the style of the list items */
      list-style: none;
    }

    .features-section li {
      /* You can use a margin property to set the space between the element and its neighbors */
      margin: 10px;
    }

    .mission-section {
      padding: 50px;
      text-align: center;
      color: white;
      background-color: #0056b3;
    }

    .contact-section {
      padding: 50px;
      text-align: center;
      color: #333;
    }

    .contact-section a {
      /* You can use a text-decoration property to set the decoration of the text */
      text-decoration: none;
      /* You can use a color property to set the color of the text */
      color: #333;
    }

    .container {
      text-align: center;
    }

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

    /* You can use a media query to apply styles based on the device width */
    @media screen and (max-width: 768px) {
      .about-page {
        width: 100%;
                }
    @media screen and (max-width: 1024px) {
          .about-page{
            width: 100%;
            }
    @media screen and (max-width: 1023px) {
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
  <!-- This is the container div for your about page -->
  <div class="about-page">
    <!-- This is the section for your about page title and description -->
    <div class="about-section">
      <h1>About Us</h1>
      <p>We are SmartSpend, a budget management system that helps you track your daily expenditure easily and efficiently. We are a team of passionate developers who believe that everyone deserves to have a clear and accurate picture of their financial situation.</p>
    </div>
    <!-- This is the section for your features and benefits -->
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
    <!-- This is the section for your mission and vision -->
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
    <!-- This is the section for your contact information -->
    <div class="contact-section">
      <h2>How to Contact Us</h2>
      <p>If you are interested in using our budget management system, you can download our application from www.smartspend.com. You can also visit our website to learn more about our features and benefits.</p> <br><br>
      <p>If you have any questions, feedback, or suggestions, you can contact us by email at [devmakaka@gmail.com] or by phone at +254 799066347. We would love to hear from you and help you with any issues or concerns you may have.</p> <br><br>
      <p>Thank you for choosing SmartSpend, the smart way to manage your budget. 😊</p>
    </div>
  </div>
</body>
</html>
