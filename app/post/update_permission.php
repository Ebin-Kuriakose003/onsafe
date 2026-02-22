<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';

header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode([
        'status'=>'error',
        'msg'=>'Unauthorized'
    ]);
    exit;
}

$assign_id = $_POST['assign_id'] ?? 0;
$can_edit  = isset($_POST['can_edit']) ? 1 : 0;
$can_delete= isset($_POST['can_delete']) ? 1 : 0;

if(!$assign_id){
    echo json_encode([
        'status'=>'error',
        'msg'=>'Invalid Assignment'
    ]);
    exit;
}

$user_id = $_SESSION['id'];
$role    = $_SESSION['role'];

/*
    RULE:
    Super Admin → Can update any assignment
    Admin       → Can update only assignments created by him
*/

if($role == ROLE_SUPERADMIN){

    $stmt = $conn->prepare("
        UPDATE post_permissions
        SET can_edit=?, can_delete=?
        WHERE assign_id=?
    ");
    $stmt->bind_param("iii", $can_edit, $can_delete, $assign_id);

} elseif($role == ROLE_ADMIN){

    $stmt = $conn->prepare("
        UPDATE post_permissions pp
        JOIN post_assignments pa ON pa.id = pp.assign_id
        SET pp.can_edit=?, pp.can_delete=?
        WHERE pp.assign_id=? AND pa.assigned_by=?
    ");
    $stmt->bind_param("iiii", $can_edit, $can_delete, $assign_id, $user_id);

} else {
    echo json_encode([
        'status'=>'error',
        'msg'=>'Access Denied'
    ]);
    exit;
}

if($stmt->execute() && $stmt->affected_rows > 0){
    echo json_encode([
        'status'=>'success',
        'msg'=>'Permission Updated'
    ]);
} else {
    echo json_encode([
        'status'=>'error',
        'msg'=>'Update Failed or Not Allowed'
    ]);
}

exit;