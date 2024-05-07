<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Get user information from session variables
$user_id = $_SESSION['user_id'];

// Query to get user's information
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

// Fetch user's information
if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
} else {
    // Handle error if user not found
    $user = null;
}

// Check if user is admin
if ($user['role'] !== 'admin') {
    // If not admin, redirect to index.php
    header("Location: index.php");
    exit();
}

// Check if thread ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect to index.php if thread ID is not provided
    header("Location: index.php");
    exit();
}

$thread_id = $_GET['id'];

// Delete the thread from the database
$sql_delete = "DELETE FROM threads WHERE id='$thread_id'";
    
if ($conn->query($sql_delete) === TRUE) {
    // Redirect to index.php after successful deletion
    header("Location: index.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
