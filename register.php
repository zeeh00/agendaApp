<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error = "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

        if ($conn->query($insert_query) === TRUE) {
            // Redirect to login page after successful registration
            header("Location: login.html");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
