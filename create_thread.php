<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Check if user is admin
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id='$user_id' AND role='admin'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows != 1) {
    // Redirect non-admin users to index.php
    header("Location: index.php");
    exit();
}

// Proceed with creating a new thread
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO threads (title, content, user_id) VALUES ('$title', '$content', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        header("location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Thread</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your stylesheet link here -->
</head>
<body>
    <div class="container">
        <h1>Create New Thread</h1>
        
        <form action="create_thread.php" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required></textarea>
            </div>
            <button type="submit">Create Thread</button>
        </form>

        <!-- Back to Threads Button -->
        <form action="index.php" method="get">
            <button type="submit">Back to Threads</button>
        </form>
    </div>
</body>
</html>
