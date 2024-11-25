<?php
    session_start();
    require 'db.php'; // Include database connection

    if ($_SESSION['user']) {
        $user = $_SESSION['user'];
        $sql = "SELECT * FROM blogs ORDER BY event_date ASC";
    } else {
        $sql = "SELECT * FROM blogs WHERE privacy_filter = 'public' ORDER BY event_date ASC";
    }


    $result = $conn->query($sql);

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Single Blog</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <h1>Photos ABCD</h1>

        <?php include 'navbar.php'; ?>
        <?php show_navbar(); ?>


        <p> This page is for viewing one blog, that was clicked on, on the home page.
            Will work this out later. </p>


    </body>
</html>