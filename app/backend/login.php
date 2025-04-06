<?php
header('Content-Type: application/json');
include_once 'connect.php';

try {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $role = $_POST['role'];

        // Check if user exists in the database
        $sql = "SELECT * FROM users WHERE (username = '$username' OR email = '$username') AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['role'] !== $role) {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(['success' => false, 'message' => 'You are not authorized for this role. Invalid role.']);
                exit();
            }

            header('HTTP/1.1 200 OK');
            echo json_encode([
                'success' => true,
                'user' => $user,
                'role' => $user['role'],
                'token' => base64_encode($user['email']),
                'intended_page' => $user['role'] === 'admin' ? '../admin/' : '../user/'
            ]);
            exit();
        } else {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            exit();
        }
    }
} catch (\Throwable $th) {
    header('HTTP/1.1 500');
    echo json_encode(['success' => false, 'message' => 'Internal server error. Something went wrong. make sure all configurations are properly done.']);
    exit();
}
