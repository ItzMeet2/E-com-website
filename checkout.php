
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
    <title>Checkout</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .checkout-container { width: 80%; margin: 20px auto; }
        .checkout-items { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .checkout-item { display: flex; align-items: center; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .checkout-item img { width: 60px; height: 60px; margin-right: 10px; object-fit: cover; }
        .item-details { flex-grow: 1; }
        .item-details h4 { margin-top: 0; margin-bottom: 5px; }
        .order-summary { text-align: right; margin-bottom: 20px; font-size: 1.1em; }
        .shipping-info { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .shipping-info h2 { margin-top: 0; margin-bottom: 10px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .place-order-button { background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 1.1em; cursor: pointer; border-radius: 5px; }
        .place-order-button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <!-- <header>
        <h1>Checkout</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">View Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span> |
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a> |
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header> -->
    <main class="checkout-container">
        <h2>Order Summary</h2>
        <?php if (!empty($cart_items)): ?>
            <div class="checkout-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="checkout-item">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <strong>Total: ₹<?php echo number_format($total_price, 2); ?></strong>
            </div>

            <div class="shipping-info">
                <h2>Shipping Information</h2>
                <form method="post" action="process_order.php">
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address:</label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="place-order-button">Place Order</button>
                </form>
            </div>

        <?php else: ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>