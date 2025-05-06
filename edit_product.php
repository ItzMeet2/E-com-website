<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
include 'INCLUDE/connection.php';

// Get product details
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product) {
        header('Location: admin_dashboard.php');
        exit();
    }
} else {
    header('Location: admin_dashboard.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock_quantity = $_POST['stock'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';

    $update_sql = "UPDATE products SET name = ?, description = ?, price = ?, 
                   stock_quantity = ?, image_url = ? WHERE product_id = ?";
    
    try {
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock_quantity, 
                         $image_url, $product_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product updated successfully!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error = "Error updating product: " . $conn->error;
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
    <title>Edit Product - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="welcome-container">
        <h1>Edit Product <img class="logo" src="IMAGES/logo.png" alt=""></h1>
    </div>

    <div class="admin-container">
        <div class="admin-header">
            <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="login-form">
            <h2>Edit Product</h2>
            <?php if (isset($error)) echo '<p class="error-message">' . htmlspecialchars($error) . '</p>'; ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock Quantity:</label>
                    <input type="number" name="stock" id="stock" min="0" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="text" name="image_url" id="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                </div>

                <input type="submit" value="Update Product" class="submit-btn">
            </form>
        </div>
    </div>
</body>
</html>