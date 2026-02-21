<?php
define('APP_INIT', true);

require '../config/database.php';
require '../config/session.php';

header('Content-Type: application/json');

$post_id   = $_POST['post_id'] ?? 0;
$editor_id = $_POST['editor_id'] ?? 0;
$can_edit  = isset($_POST['can_edit']) ? 1 : 0;
$can_delete= isset($_POST['can_delete']) ? 1 : 0;

if(!$post_id || !$editor_id){
    echo json_encode([
        'status'=>'error',
        'msg'=>'Invalid Data'
    ]);
    exit;
}

$conn->begin_transaction();

try {

    // Insert Assignment
    $stmt = $conn->prepare("
        INSERT INTO post_assignments (post_id, assigned_by, editor_id)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iii", $post_id, $_SESSION['id'], $editor_id);
    $stmt->execute();

    $assign_id = $conn->insert_id;

    // Insert Permissions
    $stmt2 = $conn->prepare("
        INSERT INTO post_permissions (assign_id, can_edit, can_delete)
        VALUES (?, ?, ?)
    ");
    $stmt2->bind_param("iii", $assign_id, $can_edit, $can_delete);
    $stmt2->execute();

    $conn->commit();

    echo json_encode([
        'status'=>'success',
        'msg'=>'Editor Assigned Successfully'
    ]);

} catch (mysqli_sql_exception $e) {

    $conn->rollback();

    if($e->getCode() == 1062){
        echo json_encode([
            'status'=>'error',
            'msg'=>'Editor already assigned to this post'
        ]);
    } else {
        echo json_encode([
            'status'=>'error',
            'msg'=>'Something went wrong'
        ]);
    }
}