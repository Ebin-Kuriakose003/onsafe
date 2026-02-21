<!DOCTYPE html>
<html>

<head>
    <title>All Posts - Viewer</title>

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

    <h2>All Posts (Viewer)</h2>

    <table id="postTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Owner</th>
                <th>Created</th>
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
                dataType: 'json', // 🔥 Important
                success: function(res) {

                    if (res.status === 'success') {

                        let rows = '';

                        if (res.data.length === 0) {
                            rows = `<tr><td colspan="4">No Posts Found</td></tr>`;
                        }

                        res.data.forEach(function(post) {

                            rows += `
                    <tr>
                        <td>${post.title}</td>
                        <td>${post.content}</td>
                        <td>${post.owner_name}</td>
                        <td>${post.created_at}</td>
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
    </script>
    </script>

</body>

</html>