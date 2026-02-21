<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';
require '../middleware/permission_check.php';

header('Content-Type: application/json');

$post_id = $_POST['post_id'] ?? 0;
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if(!$post_id || $title=='' || $content==''){
    echo json_encode(['status'=>'error','msg'=>'Invalid Data']);
    exit;
}

if($_SESSION['role'] == ROLE_SUPERADMIN){

    $stmt = $conn->prepare("
        UPDATE posts SET title=?, content=? WHERE id=?
    ");
    $stmt->bind_param("ssi", $title, $content, $post_id);

} elseif($_SESSION['role'] == ROLE_ADMIN){

    $stmt = $conn->prepare("
        UPDATE posts SET title=?, content=? 
        WHERE id=? AND owner=?
    ");
    $stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['id']);

} elseif($_SESSION['role'] == ROLE_EDITOR){

    if(!checkPostPermission($conn, $post_id, 'edit')){
        echo json_encode(['status'=>'error','msg'=>'No Edit Permission']);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE posts SET title=?, content=? WHERE id=?
    ");
    $stmt->bind_param("ssi", $title, $content, $post_id);

} else {
    echo json_encode(['status'=>'error','msg'=>'Access Denied']);
    exit;
}

if($stmt->execute()){
    echo json_encode(['status'=>'success','msg'=>'Post Updated']);
} else {
    echo json_encode(['status'=>'error','msg'=>'Update Failed']);
}