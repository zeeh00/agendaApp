<?php
session_start();
include('db.php');

// Initialize error message and suspension time variable
$error = '';
$suspend_time_remaining = 0;

// Check if there are previous login attempts and handle suspension
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    $first_attempt_time = $_SESSION['first_attempt_time'];
    $current_time = time();
    $time_diff = $current_time - $first_attempt_time;

    if ($time_diff < 30) {
        $suspend_time_remaining = 30 - $time_diff;
        $error = "Too many invalid attempts. Please try again after $suspend_time_remaining seconds.";
    } else {
        // Reset login attempts after 30 seconds
        unset($_SESSION['login_attempts']);
        unset($_SESSION['first_attempt_time']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $suspend_time_remaining <= 0) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Prepare and execute a prepared statement to retrieve user record
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stored_password_hash = $user['password'];

        if (password_verify($password, $stored_password_hash)) {
            // Passwords match, authenticate user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $stmt->close();
            unset($_SESSION['login_attempts']);
            unset($_SESSION['first_attempt_time']);
            header("Location: index.php");
            exit();
        } else {
            // Passwords don't match, handle invalid attempt
            handleInvalidLoginAttempt();
        }
    } else {
        // User not found, handle invalid attempt
        handleInvalidLoginAttempt();
    }

    $stmt->close();
}

function handleInvalidLoginAttempt() {
    global $error;
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
        $_SESSION['first_attempt_time'] = time();
    } else {
        $_SESSION['login_attempts']++;
    }

    if ($_SESSION['login_attempts'] >= 3) {
        $error = "Too many invalid attempts. Please try again after 30 seconds.";
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function startCountdown(seconds) {
            let remainingTime = seconds;
            const countdownElement = document.getElementById('countdown');
            
            const interval = setInterval(() => {
                if (remainingTime <= 0) {
                    clearInterval(interval);
                    countdownElement.innerHTML = '';
                    window.location.reload(); // Reload the page to clear suspension message
                } else {
                    countdownElement.innerHTML = `Please try again after ${remainingTime} seconds.`;
                    remainingTime--;
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <h1>!!! WELCOME TO AGENDA APP by MARTZEVANCIO DANIEL EDBERT - 2301943030 !!!</h1>
    <h2>Login</h2>
    <h3>If you already have account, please login...</h3>
    <h4>If you don't have any account yet, please register first !!!</h4>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($suspend_time_remaining > 0): ?>
        <p id="countdown">Please try again after <?php echo $suspend_time_remaining; ?> seconds.</p>
        <script>startCountdown(<?php echo $suspend_time_remaining; ?>);</script>
    <?php else: ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    <?php endif; ?>
    <a href="register.php">Register</a>
</body>
</html>
