<?php
session_start();
require_once 'db_connection.php';


if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$properties = getPropertiesFromDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProperty = [
        'name' => $_SESSION['username'],
        'location' => $_POST['location'],
        'age' => $_POST['age'],
        'floorPlan' => $_POST['floorPlan'],
        'numOfBeds' => $_POST['numOfBeds'],
        'numOfBath' => $_POST['numOfBath'],
        'garden' => $_POST['garden'],
        'price' => $_POST['price'],
        'imageURL' => $_POST['imageSelect']
    ];


    savePropertyToDatabase($newProperty);

    $_SESSION['userData']['properties'][] = $newProperty;
    header("Location: seller.php");
    exit();
}

function getPropertiesFromDatabase() {
    $conn = openDatabaseConnection();
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT * FROM properties WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    $properties = [];
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }

    $stmt->close();
    closeDatabaseConnection($conn);

    return $properties;
}

function savePropertyToDatabase($property) {
    $conn = openDatabaseConnection();

    $stmt = $conn->prepare("INSERT INTO properties (name, location, age, floorPlan, numOfBeds, numOfBath, garden, price, imageURL) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $property['name'], $property['location'], $property['age'], $property['floorPlan'], $property['numOfBeds'], $property['numOfBath'], $property['garden'], $property['price'], $property['imageURL']);

    $stmt->execute();

    $stmt->close();
    closeDatabaseConnection($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>

    <div class="logout-container">
        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
    
    <h1 class="center">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p class="center">View your property listing below or add new properties to your listing by filling out the form.</p>

    <div id="dashboard">
        <?php foreach ($properties as $property) : ?>
            <div class="property-card" onclick="viewPropertyDetails(<?php echo $property['id']; ?>)">
                <img src="<?php echo $property['imageURL']; ?>" class="property-image">
                <h3>Location: <?php echo $property['location']; ?></h3>
                <h3>Number of beds: <?php echo $property['numOfBeds']; ?></h3>
                <h3>Number of baths: <?php echo $property['numOfBath']; ?></h3>
                <h3>Price: <?php echo $property['price']; ?></h3>
                <button class="delete-button" onclick="deleteProperty('<?php echo $property['id']; ?>')">Delete</button>
            </div>
        <?php endforeach; ?>
        <div class="property-card plus-card" onclick="toggleForm()">
            <span class="plus-sign">+</span>
        </div>
    </div>

    <form id="addPropertyForm" action="" method="POST" style="display: none;">        
        
            <h2>Add New Property</h2>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required><br>
        
            <label for="age">Age of property:</label>
            <input type="text" name="age" id="age" required><br>
        
            <label for="floorPlan">Square foot:</label>
            <input type="text" name="floorPlan" id="floorPlan" required><br>
        
            <label for="numOfBeds">Number of beds:</label>
            <input type="text" name="numOfBeds" id="numOfBeds" required><br>
        
            <label for="numOfBath">Number of bathrooms:</label>
            <input type="text" name="numOfBath" id="numOfBath" required><br>
        
            <label for="garden">Is there a garden?</label>
            <input type="text" name="garden" id="garden" required><br>

            <label for="price">Price:</label>
            <input type="text" name="price" id="price" required><br>

            <label for="imageSelect">Select Image:</label>
            <select id="imageSelect" name="imageSelect" required>
                <option value="image1.jpg">Image 1</option>
                <option value="image2.jpg">Image 2</option>
                <option value="image3.jpg">Image 3</option>
                <option value="image4.jpg">Image 4</option>
            </select><br>
            
            <button type="submit">Add Property</button>
        
    </form>



    <script>
        function toggleForm() {
            var form = document.getElementById("addPropertyForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        function viewPropertyDetails(propertyId) {
        window.location.href = 'property.php?propertyId=' + propertyId;
        }

        function deleteProperty(propertyId) {
            if (confirm("Are you sure you want to delete this property?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_property.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        window.location.href = 'seller.php';
                    }  else {
                        alert("Failed to delete property. Please try again.");
                    }
                }   
            };
            xhr.send("propertyId=" + encodeURIComponent(propertyId));
        }
    }  

    </script>

</body>
</html>