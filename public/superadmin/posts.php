<!DOCTYPE html>
<html>

<head>
    <title>All Posts - Super Admin</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .active {
            color: green;
            font-weight: bold;
        }

        .inactive {
            color: red;
            font-weight: bold;
        }

        .draft {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>All Posts (Super Admin)</h2>

    <table id="postTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Owner</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        $(document).ready(function() {
            loadPosts();
        });

        function loadPosts() {
            $.ajax({
                url: '../../app/post/get_posts.php',
                type: 'GET',
                dataType: 'json', // 🔥 VERY IMPORTANT
                success: function(res) {

                    if (res.status === 'success') {

                        let rows = '';

                        res.data.forEach(function(post) {

                            rows += `
                    <tr>
                        <td>${post.title}</td>
                        <td>${post.content}</td>
                        <td>${post.owner_name}</td>
                        <td class="${post.status}">${post.status}</td>
                        <td>${post.created_at}</td>
                        <td>
                             <button onclick="editPost(${post.id}, '${post.title}', \`${post.content}\`)">Edit</button>
                             <button onclick="openAssignModal(${post.id})">Assign</button>
                            <button onclick="manageAssignments(${post.id})">Edit Assign</button>
                             <button onclick="changeStatus(${post.id}, 'active')">Activate</button>
                             <button onclick="changeStatus(${post.id}, 'inactive')">Deactivate</button>
                             <button onclick="deletePost(${post.id})">Delete</button>
                        </td>
                    </tr>
                    `;
                        });

                        $('#postTable tbody').html(rows);
                    }
                },
                error: function(xhr) {
                    console.log("Error:", xhr.responseText);
                }
            });
        }

        function editPost(id, title, content) {

            Swal.fire({
                title: 'Edit Post',
                html: `
            <input id="swal-title" class="swal2-input" value="${title}">
            <textarea id="swal-content" class="swal2-textarea">${content}</textarea>
        `,
                showCancelButton: true,
                confirmButtonText: 'Update'
            }).then((result) => {

                if (result.isConfirmed) {

                    let newTitle = document.getElementById('swal-title').value;
                    let newContent = document.getElementById('swal-content').value;

                    $.post('../../app/post/update_post.php', {
                            post_id: id,
                            title: newTitle,
                            content: newContent
                        },
                        function(response) {

                            let res = JSON.parse(response);

                            Swal.fire({
                                icon: res.status,
                                title: res.msg
                            }).then(() => {
                                if (res.status === 'success') {
                                    loadPosts();
                                }
                            });
                        });
                }
            });
        }

        function openAssignModal(postId) {

            Swal.fire({
                title: 'Assign Editor',
                html: `
            <input type="number" id="editor-id" class="swal2-input" placeholder="Editor User ID">

            <label>
                <input type="checkbox" id="can-edit"> Edit Permission
            </label><br>

            <label>
                <input type="checkbox" id="can-delete"> Delete Permission
            </label>
        `,
                showCancelButton: true,
                confirmButtonText: 'Assign'
            }).then((result) => {

                if (result.isConfirmed) {

                    let editorId = document.getElementById('editor-id').value;
                    let canEdit = document.getElementById('can-edit').checked ? 1 : 0;
                    let canDelete = document.getElementById('can-delete').checked ? 1 : 0;

                    $.post('../../app/post/assign_editor.php', {
                            post_id: postId,
                            editor_id: editorId,
                            can_edit: canEdit,
                            can_delete: canDelete
                        },
                        function(response) {

                            let res = JSON.parse(response);

                            Swal.fire({
                                icon: res.status,
                                title: res.msg
                            });
                        });
                }
            });
        }

        function changeStatus(id, status) {
            $.post('../../app/post/update_status.php', {
                    post_id: id,
                    status: status
                },
                function(response) {

                    let res = JSON.parse(response);

                    Swal.fire({
                        icon: res.status,
                        title: res.msg
                    }).then(() => {
                        if (res.status === 'success') {
                            loadPosts();
                        }
                    });
                });
        }

        function deletePost(id) {

            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post('../../app/post/delete_post.php', {
                            post_id: id
                        },
                        function(response) {

                            let res = JSON.parse(response);

                            Swal.fire({
                                icon: res.status,
                                title: res.msg
                            }).then(() => {
                                if (res.status === 'success') {
                                    loadPosts();
                                }
                            });
                        });
                }
            });
        }
        function manageAssignments(postId){

    $.ajax({
        url: '../../app/post/get_assignments.php',
        type: 'GET',
        data: {post_id: postId},
        dataType: 'json',   // 🔥 Important
        success: function(res){

            console.log("Assignment Data:", res);

            if(res.status === 'success'){

                let content = '';

                if(res.data.length === 0){
                    content = "<p>No Assignments</p>";
                }

                res.data.forEach(function(assign){

                    content += `
                        <div style="border:1px solid #ccc;padding:10px;margin-bottom:8px;">
                            <b>Editor:</b> ${assign.editor_name} <br>
                            <b>Edit:</b> ${assign.can_edit == 1 ? 'Yes' : 'No'} <br>
                            <b>Delete:</b> ${assign.can_delete == 1 ? 'Yes' : 'No'}
                        </div>
                    `;
                });

                Swal.fire({
                    title: 'Manage Assignments',
                    html: content,
                    width: 600,
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr){
            console.log("Error:", xhr.responseText);
        }
    });
}
function removeAssignment(assignId){

    Swal.fire({
        title: 'Remove Assignment?',
        icon: 'warning',
        showCancelButton: true
    }).then((result)=>{

        if(result.isConfirmed){

            $.post('../../app/post/remove_editor.php',
                {assign_id: assignId},
                function(response){

                    let res = JSON.parse(response);

                    Swal.fire({
                        icon: res.status,
                        title: res.msg
                    }).then(()=>{
                        location.reload();
                    });
                });
        }
    });
}
function updatePermission(assignId, checked, type){

    let canEdit = 0;
    let canDelete = 0;

    if(type === 'edit'){
        canEdit = checked ? 1 : 0;
    }

    if(type === 'delete'){
        canDelete = checked ? 1 : 0;
    }

    $.post('../../app/post/update_permission.php',
        {
            assign_id: assignId,
            permission_type: type,
            value: checked ? 1 : 0
        },
        function(response){

            let res = JSON.parse(response);

            Swal.fire({
                icon: res.status,
                title: res.msg,
                timer: 1000,
                showConfirmButton: false
            });
        });
}
    </script>

</body>

</html>