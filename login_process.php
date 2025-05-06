<?php
session_start(); // Start the session

include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Query the database to find the user by username
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $db_username, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, log the user in
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username;
                header("Location: index.php"); // Redirect to the homepage after successful login
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password.";
            }
        } else {
            // Username not found
            $error = "Invalid username or password.";
        }

        $stmt->close();
    }

    // If there was an error, redirect back to the login page with the error message
    if (isset($error)) {
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
} else {
    // If the page is accessed directly, redirect to the login page
    header("Location: login.php");
    exit();
}

$conn->close();
?>