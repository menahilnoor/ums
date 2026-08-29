<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connection.php';

/*
|--------------------------------------------------------------------------
| API Authentication
|--------------------------------------------------------------------------
*/

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Authorization token required'
    ]);
    exit;
}

$token = $matches[1];

$validToken = getenv('UMS_API_TOKEN');

if (!$validToken || !hash_equals($validToken, $token)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid API token'
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Only GET requests allowed
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only GET requests are allowed'
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Get a single user
| GET /api/users.php?id=123
|--------------------------------------------------------------------------
*/

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($id) {

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
            main_subject
         FROM users
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);

        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $user
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Get users
| GET /api/users.php
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            name,
            email,
            phone,
            dob,
            role,
            profile_picture,
            department,
            current_semester,
            main_subject
        FROM users
        WHERE 1=1";

$params = [];
$types = "";

/*
|--------------------------------------------------------------------------
| Optional role filter
|--------------------------------------------------------------------------
*/

if (!empty($_GET['role'])) {
    $sql .= " AND role = ?";
    $params[] = $_GET['role'];
    $types .= "s";
}

/*
|--------------------------------------------------------------------------
| Optional department filter
|--------------------------------------------------------------------------
*/

if (!empty($_GET['department'])) {
    $sql .= " AND department = ?";
    $params[] = $_GET['department'];
    $types .= "s";
}

$sql .= " ORDER BY id ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode([
    'success' => true,
    'count' => count($users),
    'users' => $users
]);

mysqli_close($conn);