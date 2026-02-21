<!DOCTYPE html>
<html>
<head>
    <title>Editor Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { padding:10px; border:1px solid #ddd; }
        th { background:#f2f2f2; }
        .tabs button { margin-right:10px; padding:6px 12px; }
        .activeTab { background:black; color:white; }
    </style>
</head>
<body>

<h2>Editor Assigned Posts</h2>

<div class="tabs">
    <button onclick="loadPosts('edit')" id="tab-edit">Edit Only</button>
    <button onclick="loadPosts('delete')" id="tab-delete">Delete Only</button>
    <button onclick="loadPosts('both')" id="tab-both">Both</button>
</div>

<br>

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

$(document).ready(function(){
    loadPosts('both'); // default load
});

function loadPosts(type){

    $('.tabs button').removeClass('activeTab');
    $('#tab-'+type).addClass('activeTab');

    $.ajax({
        url: '../../app/post/get_editor_posts.php',
        type: 'GET',
        data: {type:type},
        dataType: 'json',
        success: function(res){

            if(res.status === 'success'){

                let rows = '';

                if(res.data.length === 0){
                    rows = `<tr><td colspan="4">No Posts Found</td></tr>`;
                }

                res.data.forEach(function(post){

                    let actionButtons = '';

                    if(post.can_edit == 1){
                        actionButtons += `
                            <button onclick="editPost(${post.id}, '${post.title}', \`${post.content}\`)">
                                Edit
                            </button>
                        `;
                    }

                    if(post.can_delete == 1){
                        actionButtons += `
                            <button onclick="deletePost(${post.id})">
                                Delete
                            </button>
                        `;
                    }

                    rows += `
                        <tr>
                            <td>${post.title}</td>
                            <td>${post.content}</td>
                            <td>${post.owner_name}</td>
                            <td>${post.status}</td>
                            <td>${post.created_at}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;
                });

                $('#postTable tbody').html(rows);
            }
        }
    });
}

function editPost(id, title, content){

    Swal.fire({
        title: 'Edit Post',
        html: `
            <input id="swal-title" class="swal2-input" value="${title}">
            <textarea id="swal-content" class="swal2-textarea">${content}</textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update'
    }).then((result)=>{

        if(result.isConfirmed){

            $.post('../../app/post/update_post.php',{
                post_id:id,
                title:document.getElementById('swal-title').value,
                content:document.getElementById('swal-content').value
            }, function(response){

                let res = JSON.parse(response);

                Swal.fire({
                    icon:res.status,
                    title:res.msg
                }).then(()=>{
                    if(res.status==='success'){
                        loadPosts('both');
                    }
                });
            });
        }
    });
}

function deletePost(id){

    Swal.fire({
        title:'Are you sure?',
        icon:'warning',
        showCancelButton:true
    }).then((result)=>{

        if(result.isConfirmed){

            $.post('../../app/post/delete_post.php',{
                post_id:id
            }, function(response){

                let res = JSON.parse(response);

                Swal.fire({
                    icon:res.status,
                    title:res.msg
                }).then(()=>{
                    if(res.status==='success'){
                        loadPosts('both');
                    }
                });
            });
        }
    });
}

</script>

</body>
</html>