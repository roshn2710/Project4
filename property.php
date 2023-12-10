<?php
require_once 'db_connection.php';

if (!isset($_GET['propertyId'])) {
    header("Location: seller.php");
    exit();
}

$propertyId = $_GET['propertyId'];
$property = getPropertyDetails($propertyId);

if (!$property) {
    header("Location: seller.php");
    exit();
}

function getPropertyDetails($propertyId) {
    $conn = openDatabaseConnection();

    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $property = $result->fetch_assoc();
    } else {
        $property = false;
    }

    $stmt->close();
    closeDatabaseConnection($conn);

    return $property;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>

    <div class="logout-container">
        <button class="logout-button" onclick="window.location.href='seller.php'">SellerDash</button>
        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>



    <h1 class="center">Property Details</h1>

    <div class="property-details">
        <?php
        if ($property) {
            ?>
            <img src="<?php echo $property['imageURL']; ?>" class="property-image">
            <div class="property-info">

                <h3>Location of property: <?php echo $property['location']; ?></h3>
                <h3>Age of property: <?php echo $property['age']; ?></h3>
                <h3>Square footage: <?php echo $property['floorPlan']; ?></h3>
                <h3>Number of beds: <?php echo $property['numOfBeds']; ?></h3>
                <h3>Number of bathrooms: <?php echo $property['numOfBath']; ?></h3>
                <h3>Is there a garden? <?php echo $property['garden']; ?></h3>
                <h3>Price of house listed: <?php echo $property['price']; ?></h3>

                <button onclick="toggleUpdateForm()">Update</button>

                <form id="updatePropertyForm" action="update_property.php" method="POST" style="display: none;">
                    <input type="hidden" name="propertyId" value="<?php echo $propertyId; ?>">

                    <label for="updateLocation">New Location:</label>
                    <input type="text" name="updateLocation" id="updateLocation" value="<?php echo $property['location']; ?>" required><br>

                    <label for="updateAge">New Age:</label>
                    <input type="text" name="updateAge" id="updateAge" value="<?php echo $property['age']; ?>" required><br>

                    <label for="updateFloorPlan">New Square foot:</label>
                    <input type="text" name="updateFloorPlan" id="updateFloorPlan" value="<?php echo $property['floorPlan']; ?>" required><br>

                    <label for="updateNumOfBeds">New Number of beds:</label>
                    <input type="text" name="updateNumOfBeds" id="updateNumOfBeds" value="<?php echo $property['numOfBeds']; ?>" required><br>

                    <label for="updateNumOfBath">New Number of bathrooms:</label>
                    <input type="text" name="updateNumOfBath" id="updateNumOfBath" value="<?php echo $property['numOfBath']; ?>" required><br>

                    <label for="updateGarden">Is there a new garden?</label>
                    <input type="text" name="updateGarden" id="updateGarden" value="<?php echo $property['garden']; ?>" required><br>

                    <label for="updatePrice">New Price:</label>
                    <input type="text" name="updatePrice" id="updatePrice" value="<?php echo $property['price']; ?>" required><br>

                    <button type="submit">Update Property</button>
                </form>
            </div>
            <?php
        } else {
            echo "<p>Property not found</p>";
        }
        ?>
    </div>

    <script>
        function toggleUpdateForm() {
            var updateForm = document.getElementById("updatePropertyForm");
            updateForm.style.display = (updateForm.style.display === "none" || updateForm.style.display === "") ? "block" : "none";
        }
    </script>