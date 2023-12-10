<?php
session_start();

function authenticateUser($username, $password, $conn) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            return true;
        }
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "kanam1";
    $password = "kanam1";
    $dbname = "kanam1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (authenticateUser($username, $password, $conn)) {

        $_SESSION["username"] = $username;
        $_SESSION["userType"] = "seller";

        header("Location: seller.php");
        exit();
    } else {
        $errorMessage = '<p class="error">Authentication failed. Please check your Username and Password.</p>';
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="Styles.css">
</head>
<body>
    <div class="container">
        <h1 class="logo">Login</h1>

        <?php if (isset($errorMessage)) echo $errorMessage; ?>

        <form class="form" action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="username" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="password" required />

            <input type="submit" value="Login" />
            <h4 style="text-align: center">
                Don't Have an Account?
                <a href="SignUp.php" class="login-link">Sign Up</a>
            </h4>
        </form>
    </div>
</body>
</html>