<?php
session_start();
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
                <a href="register.php">Register</a>
            <?php endif;?>
    </div>
    </header>
    
    
    <main>
        <div class="login-form">
            <h2>Login to Your Account</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form action="process_login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <input type="submit" value="LOGIN">
                
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
        </div>
    </main>
  
    <?php include 'footer.php'; ?>
  
</body>
</html>