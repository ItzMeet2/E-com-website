<?php include 'header.php'; ?>

<?php
//session_start();
include 'INCLUDE/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$sql_cart = "SELECT c.*, p.name, p.price, p.image_url
             FROM carts c
             JOIN products p ON c.product_id = p.product_id
             WHERE c.user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

$cart_items = [];
$total_price = 0;
if ($result_cart->num_rows > 0) {
    while ($row = $result_cart->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
}

$stmt_cart->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-container { width: 80%; margin: 20px auto; }
        .cart-item { display: flex; border: 1px solid #ddd; margin-bottom: 10px; padding: 10px; border-radius: 5px; align-items: center; }
        .cart-item img { width: 80px; height: 80px; margin-right: 10px; object-fit: cover; }
        .item-details { flex-grow: 1; }
        .item-details h4 { margin-top: 0; margin-bottom: 5px; }
        .quantity-controls { display: flex; align-items: center; }
        .quantity-controls label { margin-right: 5px; }
        .quantity-controls input[type="number"] { width: 60px; padding: 5px; margin-right: 10px; }
        .remove-button { background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
        .remove-button:hover { background-color: #c82333; }
        .cart-total { text-align: right; margin-top: 20px; font-size: 1.2em; font-weight: bold; }
        .empty-cart { text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    
    <main class="cart-container">
        <?php if (isset($_GET['message'])): ?>
            <p style="color: green;"><?php echo htmlspecialchars(str_replace('_', ' ', $_GET['message'])); ?></p>
        <?php endif; ?>

        <h2>Your Cart</h2>

        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                        <div class="quantity-controls">
                            <label for="quantity_<?php echo $item['cart_id']; ?>">Quantity:</label>
                            <input type="number" id="quantity_<?php echo $item['cart_id']; ?>" name="quantity_<?php echo $item['cart_id']; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                            </div>
                    </div>
                    <form method="post" action="remove_from_cart.php">
                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <div class="cart-total">
                <strong>Total: ₹<?php echo number_format($total_price, 2); ?></strong>
            </div>

            <div>
                
                <div>
                    <a href="checkout.php"><button>Proceed to Checkout</button></a>
                </div>

        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <p><a href="index.php">Continue shopping</a></p>
            </div>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>