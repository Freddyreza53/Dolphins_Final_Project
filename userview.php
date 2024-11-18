<?php
    session_start();
    require 'db.php'; // Include database connection

    //change logic later to only show blogs based on email: only current user's blogs should show
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
        <title>User View</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <h1>Welcome to Photos ABCD</h1>

        <?php include 'navbar.php'; ?>
        <?php show_navbar(); ?>


        <div>
            <table id="blogsTable" class="display">
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
                <tbody>
                    <?php
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
                    ?>
                </tbody>
            </table>
    </body>
</html>