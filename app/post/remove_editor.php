<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';

header('Content-Type: application/json');

$assign_id = $_POST['assign_id'] ?? 0;

$stmt = $conn->prepare("
    DELETE FROM post_assignments
    WHERE id=?
");
$stmt->bind_param("i", $assign_id);

if($stmt->execute()){
    echo json_encode([
        'status'=>'success',
        'msg'=>'Editor Removed'
    ]);
} else {
    echo json_encode([
        'status'=>'error',
        'msg'=>'Remove Failed'
    ]);
}