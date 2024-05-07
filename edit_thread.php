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
    $username = $user['username'];
} else {
    // Handle error if user not found
    $username = "Unknown";
}

// Check if user is admin
$user_role = $user['role']; // Get user's role
if ($user_role !== 'admin') {
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

// Handle form submission for editing the thread
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update the thread in the database
    $sql_update = "UPDATE threads SET title='$title', content='$content' WHERE id='$thread_id'";
    
    if ($conn->query($sql_update) === TRUE) {
        // Redirect to index.php after successful update
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Thread</title>
</head>
<body>
    <h1>Edit Thread</h1>
    <form action="" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $thread['title']; ?>"><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content"><?php echo $thread['content']; ?></textarea><br><br>
        <button type="submit">Update Thread</button>
    </form>
</body>
</html>
