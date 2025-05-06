<?php
session_start();
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cart_id']) && is_numeric($_POST['cart_id']) && isset($_SESSION['user_id'])) {
        $cart_id_to_remove = $_POST['cart_id'];
        $user_id = $_SESSION['user_id'];

        // Prepare and execute the SQL query to delete the cart item
        $sql_delete = "DELETE FROM carts WHERE cart_id = ? AND user_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $cart_id_to_remove, $user_id);

        if ($stmt_delete->execute()) {
            // Item removed successfully, redirect back to the cart page
            header("Location: cart.php?message=item_removed");
            exit();
        } else {
            echo "Error removing item from cart: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } else {
        echo "Invalid request.";
    }
} else {
    // If the page is accessed directly, redirect to the cart page
    header("Location: cart.php");
    exit();
}

$conn->close();
?>