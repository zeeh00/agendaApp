<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user record from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, check password
        $user = $result->fetch_assoc();
        $stored_password_hash = $user['password'];

        // Compare provided password with stored password hash
        if (password_verify($password, $stored_password_hash)) {
            // Passwords match, authenticate user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("location: index.php"); // Redirect to homepage or dashboard
            exit();
        } else {
            // Passwords don't match, display error message
            $error = "Invalid username or password";
        }
    } else {
        // User not found, display error message
        $error = "Invalid username or password";
    }
}
?>
