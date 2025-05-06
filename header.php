<?php
// Ensure session is started if not already started in the main file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
                <span>|</span>
                <a href="register.php">Register</a>
            <?php endif; ?>
            <span>|</span>
            <a href="cart.php">Cart (0)</a>
        </nav>
        <form action="search.php" method="GET" class="search-container">
            <select name="category_id">
                <?php
                // Ensure connection is included if not already included
                // Note: Including connection here might be inefficient if already included elsewhere
                if (!isset($conn) || !$conn) {
                    include 'INCLUDE/connection.php';
                }
                
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);
                echo '<option value="">Select Category</option>';
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row['category_id'].'">'.$row['name'].'</option>';
                    }
                }
                // Avoid closing connection if it's used later on the page
                // $conn->close(); 
                ?>
            </select>
            <input type="text" name="product_name" placeholder="Product Name">
            <button type="submit" class="search-button">SEARCH</button>
        </form>
    </div>
</header>