<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lms_database');

// Application settings
define('APP_DEBUG', false);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
if (!$conn->select_db(DB_NAME)) {
    $sql = 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME . ';';
    if ($conn->query($sql) === TRUE) {
        $conn->select_db(DB_NAME);
    } else {
        die('Error creating database: ' . $conn->error);
    }
}
