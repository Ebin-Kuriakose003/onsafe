<?php
function checkRole($allowedRoles) {
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        echo "<script>alert('Unauthorized Access');window.history.back();</script>";
        exit;
    }
}