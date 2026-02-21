<?php
// Prevent direct access
if (!defined('APP_INIT')) {
    die('Direct access not allowed');
}

$host = "localhost";
$user = "root";        // change in production
$pswd = "";            // change in production
$name = "high_db";  // UPDATED

$conn = new mysqli($host, $user, $pswd, $name);

// ✅ Set PHP and MySQL timezone to IST
date_default_timezone_set('Asia/Kolkata');
mysqli_query($conn, "SET time_zone = '+05:30'");

// Check connection
if ($conn->connect_error) {
    error_log("Database Connection Failed: " . $conn->connect_error);
    die("Database connection error");
}

// Set charset
$conn->set_charset("utf8mb4");