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

// Fetch Borrowing Settings
$sql = "SELECT * FROM borrow_settings LIMIT 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

// Check if this user has exceeded max-borrowing limits
$sql = "SELECT COUNT(user_id) AS no_of_borrows FROM borrowed_books WHERE user_id='$user_id'";
$result = $conn->query($sql);
$current_borrows = $result->fetch_assoc();

if ($current_borrows['no_of_borrows'] >= $settings['max_borrow_limit']) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'User has exceeded the maximum borrowing limit.']);
    exit();
}

// Check if this book is already borrowed by the user
$sql = "SELECT COUNT(user_id) AS no_of_borrows FROM borrowed_books WHERE user_id = $user_id AND book_id = $book_id AND status = 'approved'";
$result = $conn->query($sql);
$book_borrows = $result->fetch_assoc();

if ($book_borrows['no_of_borrows'] > 0) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'This book is already borrowed by the user.']);
    exit();
}


$sql = "UPDATE borrowed_books SET status = 'approved' WHERE id = $borrow_id";
if ($conn->query($sql) === TRUE) {
    $sql = "UPDATE books SET available_copies = available_copies - 1 WHERE id = $book_id";
    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Book lent successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error lending book: ' . $conn->error]);
    }
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Error lending book: ' . $conn->error]);
}
