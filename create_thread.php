<?php
session_start();
include('db.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Proceed with creating a new thread
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    // Prepare the SQL statement
    $sql = "INSERT INTO threads (title, content, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Handle SQL error
        echo "Error: " . $conn->error;
    } else {
        // Bind parameters and execute the statement
        $stmt->bind_param("ssi", $title, $content, $_SESSION['user_id']);
        $result = $stmt->execute();

        if ($result) {
            // Redirect to index.php after successful thread creation
            header("Location: index.php");
            exit();
        } else {
            // Handle SQL error
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
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
