<?php

include 'connect.php';

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(30) NOT NULL,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(20) NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}

// insert the admin user if it doesn't exist
$sql = "SELECT * FROM users WHERE username = 'admin'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $hashedPassword = md5('123456');
    $sql = "INSERT INTO users (fullname, username, email, phone, password, role) VALUES ('Administrator', 'admin', 'admin@libraryms.com', '+44 7362 701161', '$hashedPassword', 'admin')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error inserting admin user: " . $conn->error . "<br>";
    }
}

// Create book genres table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS genres (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Populate genres table with default book genres
$genres = ['Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Historical', 'Horror', 'Mystery', 'Romance', 'Thriller', 'Fiction', 'Non-Fiction', 'Self-Help', 'Biography', 'History', 'Poetry'];
foreach ($genres as $genre) {
    $sql = "INSERT INTO genres (name) VALUES ('$genre')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error inserting genre: " . $conn->error . "<br>";
    }
}

// Create books table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS books (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(50) NOT NULL,
    genre_id INT(6) UNSIGNED NOT NULL,
    publication_year INT(4) NOT NULL,
    available_copies INT(4) NOT NULL,
    total_copies INT(4) NOT NULL,
    isbn VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (genre_id) REFERENCES genres(id)
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create borrowed_books table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS borrowed_books (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT(6) UNSIGNED NOT NULL,
    user_id INT(6) UNSIGNED NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create app borrow settings table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS borrow_settings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    borrow_duration INT(4) NOT NULL DEFAULT 7,
    fine_per_day INT(4) NOT NULL DEFAULT 1,
    max_borrow_limit INT(4) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}


$conn->close();
