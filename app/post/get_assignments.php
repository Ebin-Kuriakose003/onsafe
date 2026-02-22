<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';
require '../config/roles.php';

header('Content-Type: application/json');

$post_id = $_GET['post_id'] ?? 0;

if(!$post_id){
    echo json_encode(['status'=>'error','msg'=>'Invalid Post']);
    exit;
}

$user_id = $_SESSION['id'];
$role    = $_SESSION['role'];

$query = "
SELECT pa.id AS assign_id,
       pa.editor_id,
       u.name AS editor_name,
       pa.assigned_by,
       pp.can_edit,
       pp.can_delete
FROM post_assignments pa
JOIN users u ON u.id = pa.editor_id
JOIN post_permissions pp ON pp.assign_id = pa.id
WHERE pa.post_id = ?
";

if($role == ROLE_ADMIN){
    $query .= " AND pa.assigned_by = ?";
}

$stmt = $conn->prepare($query);

if($role == ROLE_ADMIN){
    $stmt->bind_param("ii", $post_id, $user_id);
} else {
    $stmt->bind_param("i", $post_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode(['status'=>'success','data'=>$data]);
exit;