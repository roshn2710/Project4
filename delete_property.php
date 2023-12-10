<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['propertyId'])) {
    $propertyId = $_POST['propertyId'];
    deletePropertyFromDatabase($propertyId);
} else {
    
    http_response_code(400); 
    echo 'Invalid request';
}

function deletePropertyFromDatabase($propertyId) {
    $conn = openDatabaseConnection();

    $stmt = $conn->prepare("DELETE FROM properties WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $propertyId);

    if ($stmt->execute()) {
        echo 'Property deleted successfully';
    } else {
        http_response_code(500); 
        echo 'Failed to delete property: ' . $conn->error; 
    }

    $stmt->close();
    closeDatabaseConnection($conn);
}
?>
