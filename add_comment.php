<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $thread_id = $_POST['thread_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO comments (thread_id, content, user_id) VALUES ('$thread_id', '$content', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        header("location: thread.php?id=$thread_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
