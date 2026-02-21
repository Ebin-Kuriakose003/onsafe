<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';

header('Content-Type: application/json');

$post_id = $_POST['post_id'] ?? 0;
$status  = $_POST['status'] ?? '';

if(!$post_id || !in_array($status, ['draft','active','inactive'])){
    echo json_encode(['status'=>'error','msg'=>'Invalid Data']);
    exit;
}

if($_SESSION['role'] == ROLE_SUPERADMIN){

    $stmt = $conn->prepare("
        UPDATE posts SET status=? WHERE id=?
    ");
    $stmt->bind_param("si", $status, $post_id);

} elseif($_SESSION['role'] == ROLE_ADMIN){

    $stmt = $conn->prepare("
        UPDATE posts SET status=? WHERE id=? AND owner=?
    ");
    $stmt->bind_param("sii", $status, $post_id, $_SESSION['id']);

} else {
    echo json_encode(['status'=>'error','msg'=>'Access Denied']);
    exit;
}

if($stmt->execute() && $stmt->affected_rows > 0){
    echo json_encode(['status'=>'success','msg'=>'Status Updated']);
} else {
    echo json_encode(['status'=>'error','msg'=>'Update Failed']);
}