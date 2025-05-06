<?php
session_start();
include 'INCLUDE/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to add items to cart";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Validate quantity
    if ($quantity < 1) $quantity = 1;
    if ($quantity > 10) $quantity = 10;

    // Check if product already exists in cart
    $check_sql = "SELECT cart_id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing cart item
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        if ($new_quantity > 10) $new_quantity = 10;
        
        $update_sql = "UPDATE carts SET quantity = ? WHERE cart_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
        $update_stmt->execute();
    } else {
        // Add new cart item
        $insert_sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    header("Location: cart.php");
    exit();
}

$conn->close();
?>
