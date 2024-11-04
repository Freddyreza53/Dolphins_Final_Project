<?php
    session_start();
    require 'db.php'; // Include database connection

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['user'] = '';
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Logout</title>
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


		<h1>Logout</h1>
        <p> Logout below: </p>
		<div>
			<form method="post">
				<input type="submit" name="Logout_button"value="Logout"/>
			</form>

		</div>

    </body>
</html>