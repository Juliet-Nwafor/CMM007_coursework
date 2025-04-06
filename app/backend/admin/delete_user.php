<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $sql = "SELECT * FROM users WHERE id=$user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sql = "DELETE FROM users WHERE id=$user_id";
        if ($conn->query($sql) === TRUE) {
            header('HTTP/1.1 200 OK');
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['success' => false, 'message' => 'Error deleting user: ' . $conn->error]);
        }
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
}
