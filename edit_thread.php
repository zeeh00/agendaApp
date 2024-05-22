<?php
session_start();
include('db.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect non-admin users to index.php
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
$sql_thread = "SELECT * FROM threads WHERE id=?";
$stmt = $conn->prepare($sql_thread);
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$result_thread = $stmt->get_result();

if ($result_thread->num_rows == 1) {
    $thread = $result_thread->fetch_assoc();
} else {
    // Redirect to index.php if thread not found
    header("Location: index.php");
    exit();
}

// Handle form submission for editing the thread
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_update'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update the thread in the database using prepared statements
    $sql_update = "UPDATE threads SET title=?, content=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssi", $title, $content, $thread_id);

    if ($stmt->execute()) {
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
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($thread['title']); ?>"><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content"><?php echo htmlspecialchars($thread['content']); ?></textarea><br><br>
        <button type="submit" name="confirm_update">Update Thread</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
