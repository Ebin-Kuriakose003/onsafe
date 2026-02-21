<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';

header('Content-Type: application/json');

if(!isset($_SESSION['id']) || $_SESSION['role'] != ROLE_EDITOR){
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

$editor_id = $_SESSION['id'];
$type = $_GET['type'] ?? 'both'; // edit / delete / both

$query = "
    SELECT p.id, p.title, p.content, p.status, p.created_at,
           pp.can_edit, pp.can_delete
    FROM posts p
    JOIN post_assignments pa ON pa.post_id = p.id
    JOIN post_permissions pp ON pp.assign_id = pa.id
    WHERE pa.editor_id = ?
";

if($type == 'edit'){
    $query .= " AND pp.can_edit = 1 AND pp.can_delete = 0";
} elseif($type == 'delete'){
    $query .= " AND pp.can_delete = 1 AND pp.can_edit = 0";
} elseif($type == 'both'){
    $query .= " AND pp.can_edit = 1 AND pp.can_delete = 1";
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $editor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode([
    'status'=>'success',
    'data'=>$data
]);
exit;