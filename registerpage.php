<?php
require 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, first_name, last_name, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $first_name, $last_name, $password);

    if ($stmt->execute()) {
        header("Location: loginpage.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" charset="utf8" src="scripts.js"></script>
</head>
<body>
    <h1>Photos ABCD</h1>

    <div class="navbar">
        <a href="homepage.php" >Home</a>
        <a href="loginpage.php">Login</a>
        <a href="logoutpage.php">Logout</a>
        <a href="registerpage.php">Register</a>
        <a href="viewblogs.php">View Blogs</a>
        <a href="newblogcreation.php">Create Blog</a>
     </div>


    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>