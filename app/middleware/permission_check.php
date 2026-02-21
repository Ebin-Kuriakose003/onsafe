<?php
function checkPostPermission($conn, $post_id, $type){

    $stmt = $conn->prepare("
        SELECT pp.can_edit, pp.can_delete
        FROM post_assignments pa
        JOIN post_permissions pp ON pp.assign_id = pa.id
        WHERE pa.post_id = ? AND pa.editor_id = ?
    ");

    $stmt->bind_param("ii", $post_id, $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        return false;
    }

    $perm = $result->fetch_assoc();

    if($type == 'edit' && $perm['can_edit'] == 1) return true;
    if($type == 'delete' && $perm['can_delete'] == 1) return true;

    return false;
}