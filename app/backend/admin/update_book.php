<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

if (isset($_POST['book_id']) && isset($_POST['title']) && isset($_POST['author']) && isset($_POST['quantity']) && isset($_POST['published_date']) && isset($_POST['isbn']) && isset($_POST['genre'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $published_date = $_POST['published_date'];
    $isbn = $_POST['isbn'];
    $genre = $_POST['genre'];

    $sql = "UPDATE books SET title='$title', author='$author', publication_year='$published_date', isbn='$isbn', genre_id='$genre', total_copies='$quantity' WHERE id=$book_id";
    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Book updated successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error updating book: ' . $conn->error]);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing required data. Please provide id, title, author, quantity, published_date, isbn, and genre.']);
}

