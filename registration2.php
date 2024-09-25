
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