<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Prepare and execute a prepared statement to retrieve user record
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stored_password_hash = $user['password'];

        if (password_verify($password, $stored_password_hash)) {
            // Passwords match, authenticate user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            // Passwords don't match, display error message
            $error = "Invalid username or password";
        }
    } else {
        // User not found, display error message
        $error = "Invalid username or password";
    }

    $stmt->close();
}

// If login failed or no POST request, redirect to login page with error
header("Location: login.html?error=" . urlencode($error));
exit();
?>
