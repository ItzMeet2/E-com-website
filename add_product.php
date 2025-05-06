<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock_quantity = $_POST['stock'] ?? 0;  
    $category_id = $_POST['category'] ?? '';  
    $image_url = $_POST['image_url'] ?? '';

    // Insert into database
    // Modify the SQL query
    // Update the SQL query to include category_id
    $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image_url) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $description, $price, $stock_quantity, $category_id, $image_url);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product added successfully!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error = "Error adding product: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="welcome-container">
        <h1>Add New Product <img class="logo" src="IMAGES/logo.png" alt=""></h1>
    </div>

    <div class="admin-container">
        <div class="admin-header">
            <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="login-form">
            <h2>Add Product</h2>
            <?php if (isset($error)) echo '<p class="error-message">' . htmlspecialchars($error) . '</p>'; ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock Quantity:</label>
                    <input type="number" name="stock" id="stock" min="0" required>
                </div>

                
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category" required>
                        <option value="">Select Category</option>
                        <?php
                        $cat_sql = "SELECT category_id, name FROM categories";
                        $cat_result = $conn->query($cat_sql);
                        while($category = $cat_result->fetch_assoc()) {
                            echo "<option value='" . $category['category_id'] . "'>" . 
                                 htmlspecialchars($category['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="text" name="image_url" id="image_url" required>
                </div>

                <input type="submit" value="Add Product" class="submit-btn">
            </form>
        </div>
    </div>
</body>
</html>