<?php
session_start();
include 'INCLUDE/connection.php'; // Ensure database connection file exists

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['shipping_address']) && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $shipping_address = $conn->real_escape_string($_POST['shipping_address']); // Sanitize input
        $order_date = date("Y-m-d H:i:s"); // Current timestamp

        // 1. Calculate the total amount from the cart
        $sql_cart = "SELECT c.*, p.price FROM carts c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        $total_amount = 0;
        $cart_items = [];
        if ($result_cart->num_rows > 0) {
            while ($row = $result_cart->fetch_assoc()) {
                $total_amount += $row['price'] * $row['quantity'];
                $cart_items[] = $row;
            }
        }
        $stmt_cart->close();

        if (!empty($cart_items)) {
            // 2. Insert a new record into the 'orders' table
            $sql_insert_order = "INSERT INTO orders (user_id, order_date, total_amount, shipping_address, order_status) VALUES (?, ?, ?, ?, 'Pending')";
            $stmt_insert_order = $conn->prepare($sql_insert_order);

            if ($stmt_insert_order === false) {
                echo "Error preparing statement: " . $conn->error;
                exit();
            }

            $total_amount = floatval($total_amount); // Ensure it's a float
            $stmt_insert_order->bind_param("issd", $user_id, $order_date, $shipping_address, $total_amount);

            if ($stmt_insert_order->execute()) {
                $order_id = $conn->insert_id; // Get the newly inserted order ID

                // 3. Insert records into the 'order_items' table for each cart item
                $sql_insert_item = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
                $stmt_insert_item = $conn->prepare($sql_insert_item);

                foreach ($cart_items as $item) {
                    $stmt_insert_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $stmt_insert_item->execute();
                }
                $stmt_insert_item->close();

                // 4. Clear the user's cart
                $sql_clear_cart = "DELETE FROM carts WHERE user_id = ?";
                $stmt_clear_cart = $conn->prepare($sql_clear_cart);
                $stmt_clear_cart->bind_param("i", $user_id);
                $stmt_clear_cart->execute();
                $stmt_clear_cart->close();

                // 5. Redirect to an order confirmation page
                header("Location: order_confirmation.php?order_id=" . $order_id);
                exit();

            } else {
                echo "Error creating order: " . $stmt_insert_order->error;
            }
            $stmt_insert_order->close();

        } else {
            echo "Your cart is empty. Cannot place an order.";
        }

    } else {
        echo "Error: Missing shipping address or user ID.";
    }
} else {
    header("Location: checkout.php");
    exit();
}

$conn->close();
?>
