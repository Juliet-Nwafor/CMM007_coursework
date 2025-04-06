<?php
// Check if the user is logged in using authentication token through authorization header
// Reference: https://www.php.net/apache_request_headers

$headers = apache_request_headers();

if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
    $email = base64_decode($token);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $auth_user = $result->fetch_assoc();
    }
}else{
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}
?>