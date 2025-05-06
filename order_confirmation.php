<?php include 'header.php'; ?>

<?php
//session_start();
include 'INCLUDE/connection.php';

if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    header("Location: index.php"); // Redirect if no order ID
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .confirmation-container { width: 80%; margin: 50px auto; text-align: center; border: 1px solid #ddd; padding: 30px; border-radius: 5px; }
        .confirmation-container h2 { color: green; }
        .confirmation-container p { margin-bottom: 15px; }
        .order-id { font-weight: bold; }
        .back-to-home { margin-top: 20px; }
    </style>
</head>
<body>
   
    <main class="confirmation-container">
        <h2>Thank you for your order!</h2>
        <p>Your order has been placed successfully. Your order ID is:</p>
        <p class="order-id"><?php echo htmlspecialchars($order_id); ?></p>
        <p>You will receive an email with further details shortly.</p>
        <p class="back-to-home"><a href="index.php">Back to Homepage</a></p>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>