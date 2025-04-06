<?php
header('Content-Type: application/json');
include_once '../connect.php';

$sql = "SELECT * FROM genres ORDER BY name ASC";
$result = $conn->query($sql);
$genres = array();

if ($result->num_rows > 0) {
    while ($genre = $result->fetch_assoc()) {
        $genres[] = $genre;
    }
}

header('HTTP/1.1 200 OK');
echo json_encode(['success' => true, 'genres' => $genres]);
