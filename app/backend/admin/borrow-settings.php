<?php

header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

if (isset($_POST['borrow_duration'], $_POST['max_borrow_limit'], $_POST['fine_per_day'])) {
    $borrow_duration = $_POST['borrow_duration'];
    $max_borrow_limit = $_POST['max_borrow_limit'];
    $fine_per_day = $_POST['fine_per_day'];
    $sql = "SELECT COUNT(*) as count FROM borrow_settings";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $sql = "UPDATE borrow_settings SET borrow_duration = $borrow_duration, max_borrow_limit = $max_borrow_limit, fine_per_day = $fine_per_day";
    } else {
        $sql = "INSERT INTO borrow_settings (borrow_duration, max_borrow_limit, fine_per_day) VALUES ($borrow_duration, $max_borrow_limit, $fine_per_day)";
    }

    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Borrow settings updated successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error updating borrow settings: ' . $conn->error]);
    }
    exit();
}

$sql = "SELECT * FROM borrow_settings LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $settings = $result->fetch_assoc();
    echo json_encode(['success' => true, 'settings' => $settings]);
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['success' => false, 'message' => 'Borrow settings not found.']);
}

$conn->close();
?>

