<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';

header('Content-Type: application/json');

$assign_id = $_POST['assign_id'] ?? 0;
$can_edit  = isset($_POST['can_edit']) ? 1 : 0;
$can_delete= isset($_POST['can_delete']) ? 1 : 0;

$stmt = $conn->prepare("
    UPDATE post_permissions
    SET can_edit=?, can_delete=?
    WHERE assign_id=?
");
$stmt->bind_param("iii", $can_edit, $can_delete, $assign_id);

if($stmt->execute()){
    echo json_encode([
        'status'=>'success',
        'msg'=>'Permission Updated'
    ]);
} else {
    echo json_encode([
        'status'=>'error',
        'msg'=>'Update Failed'
    ]);
}