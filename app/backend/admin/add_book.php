<?php

header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

if ($auth_user['role'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'You are not authorized to access this page.']);
    exit();
}

if (isset($_POST['title']) && isset($_POST['author']) && isset($_POST['quantity']) && isset($_POST['published_date']) && isset($_POST['isbn']) && isset($_POST['genre'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $published_date = $_POST['published_date'];
    $isbn = $_POST['isbn'];
    $genre = $_POST['genre'];

    // Upload cover image if there is
    $cover_image = null;
    if (isset($_FILES['cover_image'])) {
        $target_dir = "../assets/images/";
        if(!file_exists($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $filename = str_replace(' ', '_', strtolower($title . basename($_FILES["cover_image"]["name"])));
        $target_file = $target_dir . $filename;
        $public_path = '../backend/assets/images/' . $filename;

        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
            $cover_image = $public_path;
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your image.']);
            exit();
        }
    }

    $sql = "INSERT INTO books (title, author, publication_year, isbn, genre_id, available_copies, total_copies, cover_image) VALUES ('$title', '$author', '$published_date', '$isbn', '$genre', '$quantity', '$quantity', '$cover_image')";
    if ($conn->query($sql) === TRUE) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Book added successfully.']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'message' => 'Error adding book: ' . $conn->error]);
    }
}else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing required data. Please provide title, author, publisher, year, isbn, and genre.']);
}
