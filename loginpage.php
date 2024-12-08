<?php
    session_start();
    require 'db.php'; // Include database connection
    include 'navbar.php'; // Include navigation bar

    // Handle login request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        // Check if the user exists and the password is correct 
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name']; 
            header("Location: homepage.php");
        } else {
            echo "Invalid email or password";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Photos ABCD</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <h1>Photos ABCD</h1>
        <?php show_navbar(); // Display the navbar?>

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