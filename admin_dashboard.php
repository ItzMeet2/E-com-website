

<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
include 'INCLUDE/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cara</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="welcome-container">
        <h1>Admin Dashboard <img class="logo" src="IMAGES/logo.png" alt=""></h1>
    </div>

    <!-- Add this right after the admin-header div -->
    <div class="admin-container">
        <div class="admin-header">
            <h2>Manage Products</h2>
            <a href="add_product.php" class="add-product-btn">Add New Product</a>
        </div>

        <div class="message-container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    
        <div class="product-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM products";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                            echo "<td class='action-buttons'>
                                    <a href='edit_product.php?id=" . $row['product_id'] . "' class='edit-btn'>Edit</a>
                                    <a href='delete_product.php?id=" . $row['product_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No products found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="admin-footer">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>