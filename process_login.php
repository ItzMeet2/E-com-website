<?php
session_start();
include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT user_id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found";
        header("Location: login.php");
        exit();
    }
}

$conn->close();
?>