<?php
session_start();
include('db.php');

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
    <h1>Free Agenda App</h1>
    <?php while($row = $result->fetch_assoc()): ?>
        <h2><?php echo $row['title']; ?></h2>
        <p><?php echo $row['content']; ?></p>
    <?php endwhile; ?>
</body>
</html>
