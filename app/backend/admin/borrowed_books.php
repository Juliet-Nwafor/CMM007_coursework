<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

$sql = "SELECT 
            bb.id as borrow_id,
            bb.status,
            b.*,
            u.fullname,
            bb.borrow_date,
            bb.return_date
        FROM borrowed_books bb
        INNER JOIN books b ON bb.book_id = b.id
        INNER JOIN users u ON bb.user_id = u.id
        ORDER BY bb.id DESC";

$result = $conn->query($sql);

$books = array();

if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        $book['overdue'] = (strtotime($book['return_date']) < time()) ? true : false;
        $books[] = $book;
    }
}

echo json_encode(['success' => true, 'books' => $books]);
