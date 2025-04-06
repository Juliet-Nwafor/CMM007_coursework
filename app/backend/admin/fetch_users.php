<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

$auth_user_id = $auth_user['id'];

$sql = "SELECT * FROM users WHERE id != $auth_user_id ORDER BY fullname ASC";
$result = $conn->query($sql);
$users = array();

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        $users[] = $user;
    }
}

header('HTTP/1.1 200 OK');
echo json_encode(['success' => true, 'users' => $users]);