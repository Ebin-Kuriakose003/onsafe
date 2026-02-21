<?php
require_once __DIR__ . '/../config/session.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear session
$_SESSION = [];

// Destroy session
session_destroy();

// Prevent back button caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    icon: 'success',
    title: 'Logged Out',
    text: 'You have been logged out successfully.',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location.href = '../../public/auth/login.php';
});
</script>

</body>
</html>