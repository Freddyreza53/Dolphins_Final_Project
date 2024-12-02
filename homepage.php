<?php
    session_start();
    require 'db.php'; // Include database connection
    include 'navbar.php'; 

    if ($_SESSION['user']) {
        $user = $_SESSION['user'];
        $sql = "SELECT * FROM blogs ORDER BY event_date ASC";
    } else {
        $sql = "SELECT * FROM blogs WHERE privacy_filter = 'public' ORDER BY event_date ASC";
    }

    
    $result = $conn->query($sql);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$searchcode = $_POST['search'];
			header("Location: homepagesearch.php?id=$searchcode");
			exit();

		}

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Photos ABCD</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <h1>Welcome to Photos ABCD</h1>
        <p> Created by Team Dolphins </p>
        <?php show_navbar(); ?>


		<br>
		<div class="search-bar">
			<form method="POST" action="">
                <input type="search" id="search" name="search" placeholder="Search" required>
                <input type="submit" value="Search">
            </form>
			<form method="POST" action="">
                <input type="submit" value="View By Date">
            </form>
		</div>


        <table id="view_blogs">
            <?php
                if ($result->num_rows > 0) {
                $counter = 0;
                    while ($row = $result->fetch_assoc()) {
                         $time = time();
                         $counter++;
                         if ($counter == 0) {
                             echo "<tr>";
                         }
                         $blogid = $row["blog_id"];
                         $title = $row["title"];
                    	 $image_path = "images/" . $row["blog_id"] . "/" . $row["blog_id"];
                         $default_image = "images/default_images/default-featured-image.jpg"; // Path to the default image

                         if (!file_exists($image_path)) {
                            $image_path = $default_image;
                         }

                    	//Maybe make this look better visually.
                    	echo "<td> <div class=\"blog-info\">
                    	<img src='" . $image_path . "' alt='Image' width='100' height='100'>
                    	<p>$blogid</p> <a href=\"singleblogview.php?id=$blogid\">
                    	<p>$title</p>
                    	</div></td>";

                        if ($counter % 5 == 0 && $counter > 0) {
                            echo "</tr>";
                            if ($counter < $result->num_rows) {
                                echo "<tr>";
                            }
                        }
                    }
                }
            ?>
        </table>
    </body>
</html>