<?php
header('Content-Type: application/json');
include_once 'connect.php';
include_once 'authenticate.php';

$auth_user_id = $auth_user['id'];

$searchVal = $_GET['search'] ?? '';
$filterBy = $_GET['filter'] ?? '';

$sql = "SELECT * FROM books";

if (!empty($searchVal)) {
    $sql .= " WHERE (title LIKE '%$searchVal%' OR author LIKE '%$searchVal%' OR isbn LIKE '%$searchVal%') ";
}

if (!empty($filterBy)) {
    if (!empty($searchVal)) {
        $sql .= " AND genre_id = $filterBy ";
    }else{
        $sql .= " WHERE genre_id = $filterBy ";
    }
}

$sql .= " ORDER BY title ASC";

$result = $conn->query($sql);

$books = array();

if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        $book['genre'] = fetchGenreByID($book['genre_id'], $conn);
        $book['is_borrowed'] = checkIfBookIsBorrowed($book['id'], $auth_user_id, $conn);
        $book['is_available'] = $book['available_copies'] > 0 ? true : false;
        $books[] = $book;
    }
}

echo json_encode(['success' => true, 'books' => $books]);

function fetchGenreByID($id, $conn) {
    $sql = "SELECT * FROM genres WHERE id = $id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function checkIfBookIsBorrowed($book_id, $user_id, $conn) {
    $sql = "SELECT * FROM borrowed_books WHERE book_id = $book_id AND user_id = $user_id AND (status = 'pending' OR status = 'approved')";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

