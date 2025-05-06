<?php
session_start();
include 'INCLUDE/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $comment = trim($_POST['comment']);
    
    if (!empty($comment)) {
        $sql = "INSERT INTO comments (product_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $product_id, $user_id, $comment);
        
        if ($stmt->execute()) {
            header("Location: product_details.php?id=" . $product_id);
            exit();
        } else {
            $_SESSION['error'] = "Error posting comment: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Comment cannot be empty";
    }
    
    header("Location: product_details.php?id=" . $product_id);
    exit();
}

header("Location: index.php");
exit();
?>