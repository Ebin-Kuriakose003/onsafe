<?php
require_once '../../app/config/session.php';

$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewer Home</title>
</head>
<body>
    <p>Welcome to <?php echo htmlspecialchars($email); ?>'s home</p><br>
    <a href="./posts.php">View All Posts</a><br>
    <a href="../../index.php">Logout</a><br>
</body>
</html>