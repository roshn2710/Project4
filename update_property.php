<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyId = $_POST['propertyId'];
    $updatedLocation = $_POST['updateLocation'];
    $updatedAge = $_POST['updateAge'];
    $updatedFloorPlan = $_POST['updateFloorPlan'];
    $updatedNumOfBeds = $_POST['updateNumOfBeds'];
    $updatedNumOfBath = $_POST['updateNumOfBath'];
    $updatedGarden = $_POST['updateGarden'];
    $updatedPrice = $_POST['updatePrice'];
   
    updateProperty($propertyId, $updatedLocation, $updatedAge, $updatedFloorPlan, $updatedNumOfBeds, $updatedNumOfBath, $updatedGarden, $updatedPrice);
    header("Location: property.php?propertyId=$propertyId");
    exit();
}

function updateProperty($propertyId, $updatedLocation, $updatedAge, $updatedFloorPlan, $updatedNumOfBeds, $updatedNumOfBath, $updatedGarden, $updatedPrice) {
    $conn = openDatabaseConnection();

    $stmt = $conn->prepare("UPDATE properties SET location = ?, age = ?, floorPlan = ?, numOfBeds = ?, numOfBath = ?, garden = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $updatedLocation, $updatedAge, $updatedFloorPlan, $updatedNumOfBeds, $updatedNumOfBath, $updatedGarden, $updatedPrice, $propertyId);
    $stmt->execute();
    $stmt->close();
    closeDatabaseConnection($conn);
}
?>
