<?php
    session_start();
    require 'db.php'; // Include database connection

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$searchcode = $_POST['search'];
			header("Location: homepagesearch.php?id=$searchcode");
			exit();

		}

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Homepage Search</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <h1>Photos ABCD</h1>

        <?php include 'navbar.php'; ?>
        <?php show_navbar(); ?>

		<br>
		<div class="search-bar">
			<form method="POST" action="">
                <input type="search" id="search" name="search" placeholder="Search" required>
                <input type="submit" value="Search">
            </form>

		</div>

		<table id="view_blogs">
            <?php

			$url =  isset($_SERVER['HTTPS']) &&
        		$_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        	$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        	$currentURL = (explode("=",$url));
        	$currentsearch = $currentURL[1];
        	$sql = "SELECT * FROM blogs WHERE description LIKE '%$currentsearch%' OR title LIKE '%$currentsearch%'";
        	$result = $conn->query($sql);

			if ($currentsearch == '') {
				echo"
                <thead>
                    <tr>
                        <th>blog_id</th>
                        <th>title</th>
                        <th>description</th>
                        <th>creator_email</th>
                        <th>event_date</th>
						<th>Image</th>
                    </tr>
                </thead>
                <tbody>";

                    $sql = "SELECT * FROM blogs WHERE privacy_filter = 'public' ORDER BY event_date ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["creator_email"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["event_date"]) . "</td>";

                            $image_path = "images/" . $row["blog_id"] . "/" . $row["blog_id"];
                            $default_image = "images/default_images/default-featured-image.jpg"; // Path to the default image

                            if (!file_exists($image_path)) {
                                $image_path = $default_image;
                            }


                            // echo "<td><a href='" . $image_path . "'>View</a></td>";
                            echo "<td><img src='" . $image_path . "' alt='Image' width='100' height='100'></td>";
                            // echo "<td><a href='images/" . $row["blog_id"] . "/" . $row["blog_id"] ."'>View</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                    echo"
                </tbody>
            </table>";
			}

            elseif ($result->num_rows > 0) {
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

			else { echo"Unexpected Error";}
            ?>
        </table>


    </body>
</html>