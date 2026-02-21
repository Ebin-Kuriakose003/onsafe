<?php
define('APP_INIT', true);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../config/roles.php';
require __DIR__ . '/../config/session.php';

header('Content-Type: application/json');

// 1️⃣ Basic request check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Invalid request'
    ]);
    exit;
}

// 2️⃣ Get & sanitize input
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


if ($email === '' || $password === '') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Email and password are required'
    ]);
    exit;
}

// 3️⃣ Fetch login row
$stmt = $conn->prepare("
    SELECT u.id, u.email, u.password, r.name AS role, u.status
    FROM users u
    JOIN roles r ON u.role_id = r.id
    WHERE u.email = ?
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Invalid email or password'
    ]);
    exit;
}

$user = $result->fetch_assoc();

// 4️⃣ Verify password
// if (!password_verify($password, $user['password'])) {
//     echo json_encode([
//         'status' => 'error',
//         'msg' => 'Invalid email or password'
//     ]);
//     exit;
// }
if ($password !== $user['password']) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Invalid email or password'
    ]);
    exit;
}
// 6️⃣ Set session
$_SESSION['id']  = $user['id'];
$_SESSION['email']     = $user['email'];
$_SESSION['role'] = $user['role'];
// $_SESSION['status']    = $user['status']; // uses STATUS_* constants



// 7️⃣ Redirect by role
switch ($user['role']) {
    case ROLE_SUPERADMIN:
        $redirect = '../../public/superadmin/home.php';
        break;
    case ROLE_ADMIN:
        $redirect = '../../public/admin/home.php';
        break;

    case ROLE_EDITOR:
        $redirect = '../../public/editor/home.php';
        break;

    case ROLE_VIEWER:
        $redirect = '../../public/viewer/home.php';
        break;

    default:
        $redirect = '../../index.php';
}

echo json_encode([
    'status' => 'success',
    'msg' => 'Login successful',
    'redirect' => $redirect
]);
exit;
