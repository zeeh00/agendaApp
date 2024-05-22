<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get the thread ID from the URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$thread_id = $_GET['id'];

// Fetch the thread details
$sql_thread = "SELECT * FROM threads WHERE id='$thread_id'";
$result_thread = $conn->query($sql_thread);

if ($result_thread->num_rows == 1) {
    $thread = $result_thread->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}

// Fetch comments for the thread
$sql_comments = "SELECT comments.content, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.thread_id = '$thread_id'";
$result_comments = $conn->query($sql_comments);

// Get user information
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    $username = $user['username'];
    $user_role = $user['role'];
} else {
    $username = "Unknown";
    $user_role = "user";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($thread['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($thread['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>

        <h2>Comments</h2>
        <?php if ($result_comments->num_rows > 0): ?>
            <?php while ($comment = $result_comments->fetch_assoc()): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <h2>Add a Comment</h2>
        <form action="add_comment.php" method="post">
            <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
            <textarea name="content" required></textarea><br>
            <button type="submit">Submit</button>
        </form>

        <!-- Add New Thread Button for Admin -->
        <?php if ($user_role == 'admin'): ?>
            <form action="create_thread.php" method="get">
                <button type="submit">Add New Thread</button>
            </form>
        <?php endif; ?>

        <!-- Back to Threads Button -->
        <form action="index.php" method="get">
            <button type="submit">Back to Threads</button>
        </form>
    </div>
</body>
</html>
