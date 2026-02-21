<?php
require_once '../../app/config/session.php';

$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
</head>
<body>
    <p>Welcome to <?php echo htmlspecialchars($email); ?>'s Dashboard</p><br>
    <a href="./posts.php">View All Posts</a><br>
    <a href="./create_post.php">Create New Post</a><br>
    <a href="../../index.php">Logout</a><br>
</body>
</html>