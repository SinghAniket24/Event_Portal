<?php
session_start();

// Password to protect the admin dashboard
$admin_password = 'admin123';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate entered password
    if ($_POST['password'] == $admin_password) {
        // Set session to indicate the admin is logged in
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php'); // Redirect to the dashboard
        exit();
    } else {
        $error_message = "Incorrect password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Admin Dashboard</title>
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f3f4f6;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    h2 {
        font-size: 28px;
        color: #4a148c;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .error-msg {
        color: red;
        font-weight: bold;
        margin-bottom: 20px;
    }

    label {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
        display: block;
        font-weight: 500;
    }

    input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        margin-bottom: 20px;
        outline: none;
        transition: all 0.3s ease;
    }

    input[type="password"]:focus {
        border-color: #9575cd;
        background-color: #f9f9f9;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #4a148c;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #7b1fa2;
    }

    .link {
        margin-top: 20px;
        color: #4a148c;
        font-size: 14px;
    }

    .link a {
        text-decoration: none;
        color: #4a148c;
        font-weight: bold;
    }

    .link a:hover {
        color: #7b1fa2;
    }

    .logo {
        width: 120px;
        margin-bottom: 20px;
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 20px;
            width: 90%;
        }
    }
  </style>
</head>
<body>

<div class="login-container">
    <!-- College Logo -->
    <img src="images/college_logo.png" alt="College Logo" class="logo">
    
    <h2>Admin Login</h2>

    <?php
    if (isset($error_message)) {
        echo "<p class='error-msg'>$error_message</p>";
    }
    ?>

    <form method="POST" action="login.php">
        <label for="password">Enter Password</label>
        <input type="password" name="password" id="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>


</div>

</body>
</html>
