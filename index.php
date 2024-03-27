<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Logout Logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page after logout
    header("Location: login.html");
    exit();
}

// Get user information from session variables
$user_id = $_SESSION['user_id'];

// Query to get user's information
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

// Fetch user's information
if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    $username = $user['username'];
} else {
    // Handle error if user not found
    $username = "Unknown";
}

$sql = "SELECT * FROM threads";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda App</title>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Welcome to Free Agenda App</h1>
        <p>Logged in as: <?php echo $username; ?></p>
    </header>
    
    <!-- Logout Form -->
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
    
    <!-- Threads -->
    <?php while ($row = $result->fetch_assoc()): ?>
        <h2><?php echo $row['title']; ?></h2>
        <p><?php echo $row['content']; ?></p>
    <?php endwhile; ?>
</body>
</html>
