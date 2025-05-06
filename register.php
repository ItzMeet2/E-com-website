<?php
session_start();
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if username already exists
        $check_sql = "SELECT user_id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
 
<style>

/* Registration Form Styles */
.register-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
    background: rgba(47, 54, 64, 0.95);
}

.register-form {
    background: rgba(47, 54, 64, 0.98);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 400px;
}

.register-form h2 {
    color: #fff;
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
}

.register-form .form-group {
    margin-bottom: 20px;
}

.register-form label {
    display: block;
    color: #fff;
    margin-bottom: 8px;
    font-size: 14px;
}

.register-form input {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 14px;
}

.register-form input:focus {
    outline: none;
    border-color: #3498db;
}

.register-form button {
    width: 100%;
    padding: 12px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    margin-top: 20px;
    transition: background 0.3s ease;
}

.register-form button:hover {
    background: #2980b9;
}

.register-form p {
    text-align: center;
    margin-top: 20px;
    color: #fff;
}

.register-form a {
    color: #3498db;
    text-decoration: none;
}

.register-form a:hover {
    text-decoration: underline;
}

.register-form .error-message {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

.register-form .success-message {
    background: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

    </style>


<header>
<div class="header-content">
        <a href="admin_login.php" class="admin-login-button">Admin Login</a>
        <div class="welcome-text">
            <h1>Welcome to  <img class="logo" src="IMAGES/logo.png" alt=""></h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['username'])): ?>
                <span>|</span>
                <a>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</a>
                <span>|</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <span>|</span>
                <a href="login.php">Login</a>
            <?php endif;?>
    </div>
    </header>



<div class="register-container">
    <form class="register-form" action="register.php" method="post">
        <h2>Create an Account</h2>
        <!-- Add any error/success messages here -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit">Register</button>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
</body>
</html>