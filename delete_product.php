<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include 'INCLUDE/connection.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // First, delete related records from the carts table
    $delete_cart_sql = "DELETE FROM carts WHERE product_id = ?";
    $stmt = $conn->prepare($delete_cart_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    // Then delete the product
    $delete_product_sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_product_sql);
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting product.";
    }
    
    header('Location: admin_dashboard.php');
    exit();
} else {
    header('Location: admin_dashboard.php');
    exit();
}
?>