<?php
session_start();
include('db.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect non-admin users to index.php
    header("Location: index.php");
    exit();
}

// Check if thread ID is provided
if (!isset($_POST['id']) && !isset($_GET['confirm']) && !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Handle deletion confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes' && isset($_GET['id'])) {
    $thread_id = $_GET['id'];

    // Use prepared statements to delete comments associated with the thread
    $sql_delete_comments = "DELETE FROM comments WHERE thread_id=?";
    $stmt_comments = $conn->prepare($sql_delete_comments);
    $stmt_comments->bind_param("i", $thread_id);
    if ($stmt_comments->execute()) {
        // Use prepared statements to delete the thread
        $sql_delete_thread = "DELETE FROM threads WHERE id=?";
        $stmt_thread = $conn->prepare($sql_delete_thread);
        $stmt_thread->bind_param("i", $thread_id);
        if ($stmt_thread->execute()) {
            // Redirect to index.php after successful deletion
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting thread: " . $conn->error;
        }
    } else {
        echo "Error deleting comments: " . $conn->error;
    }
} elseif (isset($_POST['id'])) {
    // If thread ID is provided via POST, display confirmation prompt
    $thread_id = $_POST['id'];

    // Fetch thread details
    $sql_thread = "SELECT title FROM threads WHERE id=?";
    $stmt_thread = $conn->prepare($sql_thread);
    $stmt_thread->bind_param("i", $thread_id);
    $stmt_thread->execute();
    $result_thread = $stmt_thread->get_result();

    if ($result_thread->num_rows == 1) {
        $thread = $result_thread->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delete - <?php echo htmlspecialchars($thread['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Confirm Delete</h1>
        <p>Are you sure you want to delete the thread: <strong><?php echo htmlspecialchars($thread['title']); ?></strong>?</p>

        <form action="delete_thread.php" method="get">
            <input type="hidden" name="confirm" value="yes">
            <input type="hidden" name="id" value="<?php echo $thread_id; ?>">
            <button type="submit">Yes, Delete</button>
        </form>
        <form action="index.php" method="get">
            <button type="submit">No, Cancel</button>
        </form>
    </div>
</body>
</html>
<?php
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
