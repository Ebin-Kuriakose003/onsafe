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

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

if($role == ROLE_SUPERADMIN || $role == ROLE_VIEWER){

    $stmt = $conn->prepare("
        SELECT p.*, u.name AS owner_name
        FROM posts p
        JOIN users u ON p.owner = u.id
        ORDER BY p.created_at DESC
    ");

} elseif($role == ROLE_ADMIN){

    $stmt = $conn->prepare("
        SELECT p.*, u.name AS owner_name
        FROM posts p
        JOIN users u ON p.owner = u.id
        WHERE p.owner = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);

} else {
    echo json_encode(['status'=>'error','msg'=>'Access Denied']);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$posts = [];

while($row = $result->fetch_assoc()){
    $posts[] = $row;
}

echo json_encode([
    'status'=>'success',
    'data'=>$posts
]);

exit;