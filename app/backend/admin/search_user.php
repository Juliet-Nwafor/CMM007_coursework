<?php
header('Content-Type: application/json');
include_once '../connect.php';
include_once '../authenticate.php';

$auth_user_id = $auth_user['id'];

$searchVal = $_GET['search'] ?? '';
$filterBy = $_GET['filter'] ?? '';

$sql = "SELECT * FROM users WHERE id != $auth_user_id ";

if (!empty($searchVal)) {
    $sql .= " AND (fullname LIKE '%$searchVal%' OR username LIKE '%$searchVal%' OR email LIKE '%$searchVal%') ";
}

if (!empty($filterBy)) {
    $sql .= " AND role = '$filterBy' ";
}

$sql .= " ORDER BY fullname ASC";

$result = $conn->query($sql);

$users = array();

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        $users[] = $user;
    }
}

echo json_encode(['success' => true, 'users' => $users]);

