<!DOCTYPE html>
<html>

<head>
    <title>Signup Page</title>
    <link rel="stylesheet" type="text/css" href="Styles.css">
</head>

<body>

    <div class="container">
        <h1 class="logo">Sign Up</h1>

        <?php
        $servername = "localhost";
        $username = "kanam1";
        $password = "kanam1";
        $dbname = "kanam1";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create a users table if it doesn't exist
        $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            username VARCHAR(255) NOT NULL,
                            email VARCHAR(255) NOT NULL,
                            password VARCHAR(255) NOT NULL
                        )";
        $conn->query($createTableSQL);

        // Function to handle form submissions
        function handleFormSubmission($conn)
        {
            // Collect form data
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
           
            // Hash the password before storing in the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the users table
            $insertDataSQL = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertDataSQL);
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();

            // Redirect to a success page or login page
            header("Location: Login.php");
            exit();
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            handleFormSubmission($conn);
        }

        ?>

        <form class="form" action="" method="post">
		
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="username" required />
			
			<label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="email" required /> 

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="password" required />
			
            <input type="submit" value="Sign Up" />
            <h4 style="text-align: center">
                Already have an account?
                <a href="Login.php" class="login-link">Login</a>
            </h4>
        </form>
    </div>
</body>

</html>