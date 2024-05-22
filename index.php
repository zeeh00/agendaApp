<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Logout Logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page after logout
    header("Location: login.php");
    exit();
}

// Get user information from session variables
$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);

// Query to get user's information (using prepared statement)
$sql_user = "SELECT * FROM users WHERE id=?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Fetch user's information
if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $user_role = htmlspecialchars($user['role']);
} else {
    // Handle error if user not found
    $username = "Unknown";
    $user_role = "user";
}

// Fetch threads from the database (using prepared statement)
$sql_threads = "SELECT * FROM threads";
$stmt_threads = $conn->prepare($sql_threads);
$stmt_threads->execute();
$result = $stmt_threads->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda App - Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Free Agenda App</h1>
        <p>Logged in as: <?php echo $username; ?> (Role: <?php echo $user_role; ?>)</p>
        <p>Click on the specific thread to view and add comments !!!</p>

        <!-- Logout Form -->
        <form action="" method="post">
            <button type="submit" name="logout">Logout</button>
        </form>

        <!-- Add Thread Button for Admin -->
        <?php if ($user_role == 'admin'): ?>
            <form action="create_thread.php" method="get">
                <button type="submit">Add New Thread</button>
            </form>
        <?php endif; ?>

        <div id="threads">
            <!-- Threads -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="thread">
                    <h2><a href="thread.php?id=<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['title']); ?></a></h2>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    
                    <!-- Edit and Delete Buttons for Admin -->
                    <?php if ($user_role == 'admin'): ?>
                        <form action="edit_thread.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <form action="delete_thread.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
