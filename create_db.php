<?php
// Database connection parameters
$db_host = 'localhost';  // MySQL server host
$db_user = 'root';   // MySQL username
$db_pass = '';   // MySQL password
$db_name = 'meteo';   // Database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create the 'meteo' table
$sql = "CREATE TABLE meteo (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dt VARCHAR(8),
    hh VARCHAR(4),
    temp DECIMAL(3,2),
    pression INT,
    humidite DECIMAL(3,2),
    rosee DECIMAL(3,2)
)";

// Execute SQL query to create the table
if ($conn->query($sql) === TRUE) {
    echo "Table 'meteo' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>