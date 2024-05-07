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

// Fetch the thread from the database
$sql_thread = "SELECT * FROM threads WHERE id='$thread_id'";
$result_thread = $conn->query($sql_thread);

if ($result_thread->num_rows == 1) {
    $thread = $result_thread->fetch_assoc();
} else {
    // Redirect to index.php if thread not found
    header("Location: index.php");
    exit();
}

// Handle deletion if confirmed
if (isset($_POST['confirm_delete'])) {
    // Delete the thread from the database
    $sql_delete = "DELETE FROM threads WHERE id='$thread_id'";
        
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect to index.php after successful deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Thread</title>
</head>
<body>
    <h1>Delete Thread</h1>
    <p>Are you sure you want to delete this thread?</p>
    <p>Title: <?php echo $thread['title']; ?></p>
    <p>Content: <?php echo $thread['content']; ?></p>
    <form action="" method="post">
        <button type="submit" name="confirm_delete">Yes, Delete</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
