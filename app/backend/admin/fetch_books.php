<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

$sql = "SELECT * FROM books ORDER BY title ASC";
$result = $conn->query($sql);
$books = array();

if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        $book['genre'] = fetchGenreByID($book['genre_id'], $conn); // Get the book genre
        $books[] = $book;
    }
}

header('HTTP/1.1 200 OK');
echo json_encode(['success' => true, 'books' => $books]);

function fetchGenreByID($genre_id, $conn){
    $sql = "SELECT * FROM genres WHERE id = '$genre_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}
