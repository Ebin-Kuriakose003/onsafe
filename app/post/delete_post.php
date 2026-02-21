<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';
require '../middleware/permission_check.php';

header('Content-Type: application/json');

$post_id = $_POST['post_id'] ?? 0;

if(!$post_id){
    echo json_encode(['status'=>'error','msg'=>'Invalid Data']);
    exit;
}

if($_SESSION['role'] == ROLE_SUPERADMIN){

    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $post_id);

} elseif($_SESSION['role'] == ROLE_ADMIN){

    $stmt = $conn->prepare("
        DELETE FROM posts WHERE id=? AND owner=?
    ");
    $stmt->bind_param("ii", $post_id, $_SESSION['id']);

} elseif($_SESSION['role'] == ROLE_EDITOR){

    if(!checkPostPermission($conn, $post_id, 'delete')){
        echo json_encode(['status'=>'error','msg'=>'No Delete Permission']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $post_id);

} else {
    echo json_encode(['status'=>'error','msg'=>'Access Denied']);
    exit;
}

if($stmt->execute() && $stmt->affected_rows > 0){
    echo json_encode(['status'=>'success','msg'=>'Post Deleted']);
} else {
    echo json_encode(['status'=>'error','msg'=>'Delete Failed']);
}