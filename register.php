<?php
session_start();
include('db.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Remove leading/trailing whitespaces
    $password = $_POST['password'];

    // Validate username and password
    if (strlen($username) < 6 || strlen($password) < 8) {
        $error = "Username must be at least 6 characters long and password must be at least 8 characters long.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Insert new user into database with default role 'user'
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $default_role = 'user';
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $default_role);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error = "Error: Unable to register. Please try again later.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Register</h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" minlength="6" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" minlength="8" required>
        <button type="submit">Register</button>
    </form>
    <br>
    <form action="login.php" method="get">
        <button type="submit">Go to Login</button>
    </form>
</body>
</html>
