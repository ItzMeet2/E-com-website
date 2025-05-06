<!-- <?php
session_start();
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['action'])) {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];
    
    // Get current quantity
    $sql = "SELECT c.quantity, p.price FROM carts c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.cart_id = ? AND c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    
    $new_quantity = $action === 'increase' ? $item['quantity'] + 1 : max(1, $item['quantity'] - 1);
    
    // Update quantity
    $sql_update = "UPDATE carts SET quantity = ? WHERE cart_id = ? AND user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $new_quantity, $cart_id, $_SESSION['user_id']);
    $stmt_update->execute();
    
    // Calculate new totals
    $subtotal = $new_quantity * $item['price'];
    
    // Get cart total
    $sql_total = "SELECT SUM(c.quantity * p.price) as total 
                  FROM carts c 
                  JOIN products p ON c.product_id = p.product_id 
                  WHERE c.user_id = ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $_SESSION['user_id']);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total = $result_total->fetch_assoc()['total'];
    
    echo json_encode([
        'success' => true,
        'quantity' => $new_quantity,
        'subtotal' => number_format($subtotal, 2),
        'total' => number_format($total, 2)
    ]);
    exit;
}

$conn->close();
?> -->