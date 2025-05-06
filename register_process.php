<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'INCLUDE/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Basic input validation
    if (empty($username) || empty($password) || empty($email)) {
        echo "Error: Username, password, and email are required.";
        exit();
    } elseif (strlen($username) < 3 || strlen($password) < 6) {
        echo "Error: Username must be at least 3 characters and password at least 6 characters.";
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format.";
        exit();
    } else {
        // Check if username already exists
        $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        if ($stmt_check) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                echo "Error: Username already exists. Please choose a different one.";
                exit();
            } else {
                // Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database (include email)
                $stmt_insert = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                if ($stmt_insert) {
                    $stmt_insert->bind_param("sss", $username, $hashed_password, $email);

                    if ($stmt_insert->execute()) {
                        echo "Success: Registration successful! You can now <a href='login.php'>log in</a>.";
                        exit();
                    } else {
                        echo "Error: Registration failed. Execute Error: " . $stmt_insert->error;
                        echo "<br>";
                        if ($stmt_insert->errno) {
                            echo "MySQL Error (" . $stmt_insert->errno . "): " . $stmt_insert->error;
                        }
                        exit();
                    }
                    $stmt_insert->close();
                } else {
                    echo "Error: Registration failed. Prepare Insert Error: " . $conn->error;
                    exit();
                }
            }
            $stmt_check->close();
        } else {
            echo "Error: Registration failed. Prepare Check Error: " . $conn->error;
            exit();
        }
    }
} else {
    echo "Error: Invalid request method.";
    exit();
}

$conn->close();
?>