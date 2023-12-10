<?php
session_start();
function openDatabaseConnection() {
    $host = "localhost";
    $user = "kanam1";
    $pass = "kanam1";
    $dbname = "kanam1";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	
	$sql = "CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(255) NOT NULL,
    age VARCHAR(50) NOT NULL,
    floorPlan VARCHAR(50) NOT NULL,
    numOfBeds VARCHAR(50) NOT NULL,
    numOfBath VARCHAR(50) NOT NULL,
    garden VARCHAR(50) NOT NULL,
    price VARCHAR(50) NOT NULL,
    imageSelect VARCHAR(50) NOT NULL
)";

    return $conn;
}

function closeDatabaseConnection($conn) {
    $conn->close();
}
?>
