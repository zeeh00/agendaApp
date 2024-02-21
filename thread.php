<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda App - Thread</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    include('db.php');
    $thread_id = $_GET['id'];
    $sql = "SELECT * FROM threads WHERE id=$thread_id";
    $result = $conn->query($sql);
    $thread = $result->fetch_assoc();
    ?>
    <h1><?php echo $thread['title']; ?></h1>
    <p><?php echo $thread['content']; ?></p>

    <h2>Comments</h2>
    <!-- Comments will be dynamically loaded here -->

    <form action="add_comment.php" method="post">
        <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
        <label for="content">Add Comment:</label>
        <textarea id="content" name="content" required></textarea>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
