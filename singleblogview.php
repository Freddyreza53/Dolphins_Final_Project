<?php
    // Start the session
    session_start();
    require 'db.php'; // Include database connection

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


        <table id="view_blog">
        		<?php
        		    //displaying the single blog
        			$url =  isset($_SERVER['HTTPS']) &&
        				$_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        			$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        			$currentID = (explode("=",$url));
        			$currentblog = $currentID[1];
        			$sql = "SELECT * FROM blogs WHERE blog_id = '$currentblog'";
        			$result = $conn->query($sql);



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
        					$description = $row["description"];
        					$creatoremail = $row["creator_email"];
        					$eventdate = $row["event_date"];
        					$image_path = "images/" . $row["blog_id"] . "/" . $row["blog_id"];
                            $default_image = "images/default_images/default-featured-image.jpg"; // Path to the default image

                            if (!file_exists($image_path)) {
                                $image_path = $default_image;
                            }


        					echo "<td> <div class=\"blog-info\">
        					<img src='" . $image_path . "' alt='Image' width='100' height='100'>
        					<p>Blog ID: $blogid</p>
        					<p>Title: $title</p>
        					<p>$description</p>
        					<p>Created by: $creatoremail</p>
        					<p>Created on: $eventdate</p>
        					</div></td>";


                        }
                    }
                ?>
        </table>


    </body>
</html>