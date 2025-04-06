<?php

    $conn = new mysqli('localhost', 'root', '');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
     $sql = 'CREATE DATABASE IF NOT EXISTS lms_database';
    if ($conn->query($sql) === TRUE) {
        $conn->select_db('lms_database');
    } else {
        die('Error creating database: ' . $conn->error);
    }
    
function createConnectFile() {
    $connectFilePath = 'connect.php';
    $connectContent = "<?php\n\n// Database configuration\n";
    $connectContent .= "define('DB_HOST', 'localhost');\n";
    $connectContent .= "define('DB_USER', 'root');\n";
    $connectContent .= "define('DB_PASS', '');\n";
    $connectContent .= "define('DB_NAME', 'lms_database');\n\n";
    $connectContent .= "// Application settings\n";
    $connectContent .= "define('APP_DEBUG', false);\n";
    
    // Create the database if it does not exist
    $connectContent .= "\$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);\n";
    $connectContent .= "if (\$conn->connect_error) {\n";
    $connectContent .= "    die('Connection failed: ' . \$conn->connect_error);\n";
    $connectContent .= "}\n";
    $connectContent .= "if (!\$conn->select_db(DB_NAME)) {\n";
    $connectContent .= "    \$sql = 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME . ';';\n";
    $connectContent .= "    if (\$conn->query(\$sql) === TRUE) {\n";
    $connectContent .= "        \$conn->select_db(DB_NAME);\n";
    $connectContent .= "    } else {\n";
    $connectContent .= "        die('Error creating database: ' . \$conn->error);\n";
    $connectContent .= "    }\n";
    $connectContent .= "}\n";
    
    // Write the content to the file
    if (file_put_contents($connectFilePath, $connectContent)) {
        if (file_exists('create_tables.php')) {
            include 'create_tables.php';
        }
        return true;
    } else {
        return false;
    }
}

createConnectFile();
?>
