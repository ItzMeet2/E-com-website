


<?php
session_start();
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cara</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #2f3640;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: #2980b9;
        }

        .login-links {
            text-align: center;
            margin-top: 20px;
        }

        .login-links a {
            color: #3498db;
            text-decoration: none;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to <img class="logo" src="IMAGES/logo.png" alt=""></h1>
    </div>

    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo '<p class="error-message">' . $error . '</p>'; ?>
        <form method="post" action="" class="login-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <input type="submit" value="LOGIN" class="submit-btn">
        </form>
        <div class="login-links">
            <a href="index.php">Back to Home</a>
        </div>
    </div>
    
</body>
</html>