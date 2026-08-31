<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed.'
    ]);
    exit;
}

require_once __DIR__ . '/../connection.php';

/*
|--------------------------------------------------------------------------
| API Token Authentication
|--------------------------------------------------------------------------
*/

$apiToken = getenv('UMS_API_TOKEN');

$headers = getallheaders();
$authorization = $headers['Authorization'] ?? '';
$providedToken = '';

if (preg_match('/^Bearer\s+(.+)$/i', trim($authorization), $matches)) {
    $providedToken = trim($matches[1]);
}

if (
    !$apiToken ||
    !$providedToken ||
    !hash_equals($apiToken, $providedToken)
) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Read JSON Request
|--------------------------------------------------------------------------
*/

$input = json_decode(file_get_contents('php://input'), true);

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = trim($input['role'] ?? '');

if ($email === '' || $password === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Find User
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        id,
        name,
        email,
        phone,
        dob,
        role,
        profile_picture,
        department,
        current_semester,
        main_subject,
        password
     FROM users
     WHERE email = ?
     LIMIT 1"
);

if (!$stmt) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Database query preparation failed.'
    ]);

    exit;
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid email or password.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Verify Password
|--------------------------------------------------------------------------
*/

if ($password !== $user['password']) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid email or password.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Optional Role Verification
|--------------------------------------------------------------------------
*/

if ($role !== '' && $role !== $user['role']) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Role does not match this account.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Remove Password Before Response
|--------------------------------------------------------------------------
*/

unset($user['password']);

/*
|--------------------------------------------------------------------------
| Successful Login Response
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([
    'success' => true,
    'message' => 'Login successful.',
    'data' => [
        'user' => $user
    ]
]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
