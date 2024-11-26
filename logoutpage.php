<?php
    session_start();
    require 'db.php'; // Include database connection

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['user'] = '';
        header("Location: homepage.php");
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

        	<?php include 'navbar.php'; ?>
            <?php show_navbar(); ?>


		<h1>Logout</h1>
        <p> Logout below: </p>
		<div>
			<form method="post">
				<input type="submit" name="Logout_button"value="Logout"/>
			</form>

		</div>

    </body>
</html>