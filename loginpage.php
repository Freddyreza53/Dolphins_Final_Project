<?php
    session_start();
    require 'db.php'; // Include database connection

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: homepage.php");
        } else {
            echo "Invalid email or password";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
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

        <h1>Login</h1>
        <p> Enter login information. </p>

        <div>
            <form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </body>
</html>