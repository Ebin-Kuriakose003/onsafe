<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h2>Create New Post</h2>

    <form id="createPostForm">
        <label>Title</label><br>
        <input type="text" name="title" required><br><br>

        <label>Content</label><br>
        <textarea name="content" rows="5" required></textarea><br><br>

        <button type="submit">Create Post</button>
    </form>

    <script>
        $('#createPostForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '../../app/post/create_post.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {

                    try {
                        let res = JSON.parse(response);

                        Swal.fire({
                            icon: res.status,
                            title: res.msg
                        }).then(() => {
                            if (res.status === 'success') {
                                location.reload();
                            }
                        });

                    } catch (e) {
                        console.log("Invalid JSON:", response);
                    }
                },
                error: function(xhr) {
                    console.log("Server Error:", xhr.responseText);
                }
            });
        });
    </script>

</body>

</html>