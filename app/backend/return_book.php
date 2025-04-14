<?php

header('Content-Type: application/json');
include_once 'connect.php';
include_once 'authenticate.php';

$auth_user_id = $auth_user['id']; // Get user from authentication (logged in user)
$book_id = $_POST['book_id'] ?? '';

if (empty($book_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing required data. Could not find the selected record']);
    exit();
}

// Check if this book is borrowed by the user
$sql = "SELECT * FROM borrowed_books WHERE book_id = $book_id AND user_id = $auth_user_id AND (status = 'pending' OR status = 'approved')";
$result = $conn->query($sql);
$borrow_request = $result->fetch_assoc();
if (empty($borrow_request)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'This book is not borrowed by you.']);
    exit();
}
$user_id = $borrow_request['user_id'];
$book_id = $borrow_request['book_id'];
$borrow_id = $borrow_request['id'];

// Check if the borrow request is already pending, then delete it
if ($borrow_request['status'] == 'pending') {
    $sql = "DELETE FROM borrowed_books WHERE id = $borrow_id";
} else {
    // Update the borrowed book record by setting the status to 'returned'
    $sql = "UPDATE borrowed_books SET status = 'returned' WHERE id = $borrow_id";
}
if ($conn->query($sql) === TRUE) {
    // Increase the copies available quantity in book table
    // $sql = "UPDATE books SET available_copies = available_copies + 1 WHERE id = $book_id";
    // if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Book returned successfully.']);
    // } else {
    //     header('HTTP/1.1 500 Internal Server Error');
    //     echo json_encode(['success' => false, 'message' => 'Error returning book: ' . $conn->error]);
    // }
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Error returning borrowed book: ' . $conn->error]);
}
