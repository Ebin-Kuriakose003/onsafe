<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';

header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

if(!in_array($_SESSION['role'], [ROLE_SUPERADMIN, ROLE_ADMIN])){
    echo json_encode(['status'=>'error','msg'=>'Access Denied']);
    exit;
}

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if($title == '' || $content == ''){
    echo json_encode(['status'=>'error','msg'=>'All fields required']);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO posts (title, content, owner, status)
    VALUES (?, ?, ?, 'active')
");

$stmt->bind_param("ssi", $title, $content, $_SESSION['id']);

if($stmt->execute()){
    echo json_encode([
        'status'=>'success',
        'msg'=>'Post Created Successfully'
    ]);
} else {
    echo json_encode([
        'status'=>'error',
        'msg'=>'Failed to Create Post'
    ]);
}

exit;