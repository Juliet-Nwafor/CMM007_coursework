<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

if (isset($_POST['user_id']) && isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role'])) {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET fullname='$fullname', username='$username', email='$email', phone='$phone', role='$role' WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $conn->error]);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing required data. Please provide id, fullname, username, email, and role.']);
}

