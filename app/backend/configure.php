<?php
header('Content-Type: application/json');

$filePath = 'config.php';

// Check if the file exists
if (!file_exists($filePath)) {
    // Check if POST request contains required data
    if (isset($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database_name'])) {
        $hostname = $_POST['hostname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $databaseName = str_replace(' ', '_', strtolower($_POST['database_name']));

        // Create a function that creates the connect.php file and establish a connection to the database
        $configContent = "<?php\n\n";
        $configContent .= "    \$conn = new mysqli('$hostname', '$username', '$password');\n";
        $configContent .= "    if (\$conn->connect_error) {\n";
        $configContent .= "        die('Connection failed: ' . \$conn->connect_error);\n";
        $configContent .= "    }\n";
        $configContent .= "    \n";
        $configContent .= "     \$sql = 'CREATE DATABASE IF NOT EXISTS $databaseName';\n";
        $configContent .= "    if (\$conn->query(\$sql) === TRUE) {\n";
        $configContent .= "        \$conn->select_db('$databaseName');\n";
        $configContent .= "    } else {\n";
        $configContent .= "        die('Error creating database: ' . \$conn->error);\n";
        $configContent .= "    }\n";
        $configContent .= "    \n";        
        $configContent .= "function createConnectFile() {\n";
        $configContent .= "    \$connectFilePath = 'connect.php';\n";
        $configContent .= "    \$connectContent = \"<?php\\n\\n// Database configuration\\n\";\n";
        $configContent .= "    \$connectContent .= \"define('DB_HOST', '$hostname');\\n\";\n";
        $configContent .= "    \$connectContent .= \"define('DB_USER', '$username');\\n\";\n";
        $configContent .= "    \$connectContent .= \"define('DB_PASS', '$password');\\n\";\n";
        $configContent .= "    \$connectContent .= \"define('DB_NAME', '$databaseName');\\n\\n\";\n";
        $configContent .= "    \$connectContent .= \"// Application settings\\n\";\n";
        $configContent .= "    \$connectContent .= \"define('APP_DEBUG', false);\\n\";\n";
        $configContent .= "    \n";
        $configContent .= "    // Create the database if it does not exist\n";
        $configContent .= "    \$connectContent .= \"\\\$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);\\n\";\n";
        $configContent .= "    \$connectContent .= \"if (\\\$conn->connect_error) {\\n\";\n";
        $configContent .= "    \$connectContent .= \"    die('Connection failed: ' . \\\$conn->connect_error);\\n\";\n";
        $configContent .= "    \$connectContent .= \"}\\n\";\n";
        $configContent .= "    \$connectContent .= \"if (!\\\$conn->select_db(DB_NAME)) {\\n\";\n";
        $configContent .= "    \$connectContent .= \"    \\\$sql = 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME . ';';\\n\";\n";
        $configContent .= "    \$connectContent .= \"    if (\\\$conn->query(\\\$sql) === TRUE) {\\n\";\n";
        $configContent .= "    \$connectContent .= \"        \\\$conn->select_db(DB_NAME);\\n\";\n";
        $configContent .= "    \$connectContent .= \"    } else {\\n\";\n";
        $configContent .= "    \$connectContent .= \"        die('Error creating database: ' . \\\$conn->error);\\n\";\n";
        $configContent .= "    \$connectContent .= \"    }\\n\";\n";
        $configContent .= "    \$connectContent .= \"}\\n\";\n";
        $configContent .= "    \n";
        $configContent .= "    // Write the content to the file\n";
        $configContent .= "    if (file_put_contents(\$connectFilePath, \$connectContent)) {\n";
        $configContent .= "        if (file_exists('create_tables.php')) {\n";
        $configContent .= "            include 'create_tables.php';\n";
        $configContent .= "        }\n";
        $configContent .= "        return true;\n";
        $configContent .= "    } else {\n";
        $configContent .= "        return false;\n";
        $configContent .= "    }\n";
        $configContent .= "}\n";
        $configContent .= "\n";
        $configContent .= "createConnectFile();\n";
        $configContent .= "?>\n";

        // Write the content to the file
        if (file_put_contents($filePath, $configContent)) {
            header('HTTP/1.1 200 OK');
            echo json_encode(['success' => true, 'message' => 'config.php file created successfully.']);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['success' => false, 'message' => 'Failed to create config.php file.']);
        }
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['success' => false, 'message' => 'Missing required data. Please provide hostname, username, password, and database name.']);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'config.php file already exists.']);
}

