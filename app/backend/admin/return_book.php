<?php

header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

$borrow_id = $_POST['borrow_id'] ?? '';

if (empty($borrow_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing required data. Could not find the selected record']);
    exit();
}

// Check if the id is valid
$sql = "SELECT * FROM borrowed_books WHERE id = $borrow_id";
$result = $conn->query($sql);
$borrow_request = $result->fetch_assoc();
if (empty($borrow_request)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Invalid record ID.']);
    exit();
}
$user_id = $borrow_request['user_id'];
$book_id = $borrow_request['book_id'];

// Delete the borrowed book record
$sql = "DELETE FROM borrowed_books WHERE id = $borrow_id";
if ($conn->query($sql) === TRUE) {
    // Increase the copies available quantity in book table
    $sql = "UPDATE books SET available_copies = available_copies + 1 WHERE id = $book_id";
    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Book returned successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error returning book: ' . $conn->error]);
    }
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Error deleting borrowed book: ' . $conn->error]);
}

