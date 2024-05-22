<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $thread_id = $_POST['thread_id'];
    $content = $_POST['content'];

    // Insert the new comment into the database
    $sql = "INSERT INTO comments (thread_id, user_id, content) VALUES ('$thread_id', '$user_id', '$content')";

    if ($conn->query($sql) === TRUE) {
        header("Location: thread.php?id=" . $thread_id);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
