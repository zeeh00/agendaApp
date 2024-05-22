<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if thread ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$thread_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

// Fetch the thread details (using prepared statement)
$stmt_thread = $conn->prepare("SELECT * FROM threads WHERE id=?");
$stmt_thread->bind_param("i", $thread_id);
$stmt_thread->execute();
$result_thread = $stmt_thread->get_result();

if ($result_thread->num_rows == 1) {
    $thread = $result_thread->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}

// Fetch comments for the thread (using prepared statement)
$stmt_comments = $conn->prepare("SELECT comments.content, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.thread_id = ?");
$stmt_comments->bind_param("i", $thread_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

// Get user information
$user_id = $_SESSION['user_id'];
$stmt_user = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

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
