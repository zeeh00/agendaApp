<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Validate and sanitize input
$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_VALIDATE_INT);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $thread_id && $content) {
    $user_id = $_SESSION['user_id'];

    // Use prepared statement to insert the new comment into the database
    $sql = "INSERT INTO comments (thread_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $thread_id, $user_id, $content);

    if ($stmt->execute()) {
        // Redirect to the thread page after successful comment addition
        header("Location: thread.php?id=" . $thread_id);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Invalid or missing input, redirect to index.php
    header("Location: index.php");
    exit();
}
?>
