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
$sql_user = "SELECT role FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    if ($user['role'] != 'admin') {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Check if thread ID is provided
if (!isset($_POST['id']) && !isset($_GET['confirm']) && !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['id'])) {
    $thread_id = $_POST['id'];
    
    // Fetch thread details
    $sql_thread = "SELECT title FROM threads WHERE id='$thread_id'";
    $result_thread = $conn->query($sql_thread);

    if ($result_thread->num_rows != 1) {
        header("Location: index.php");
        exit();
    }

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
} elseif (isset($_GET['confirm']) && $_GET['confirm'] == 'yes' && isset($_GET['id'])) {
    $thread_id = $_GET['id'];

    // Delete the thread
    $sql_delete = "DELETE FROM threads WHERE id='$thread_id'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>
