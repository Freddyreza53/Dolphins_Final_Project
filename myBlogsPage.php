<?php
    session_start();
    require 'dbCommonRequests.php'; // Include database connection
    include 'navbar.php';

    // Ensure the user is logged in
    if (!isset($_SESSION['email'])) {
        echo "Error: User is not logged in.";
        exit();
    }

    $user_email = $_SESSION['email'];

    // Handle delete request for blogs
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_blog"])) {
        $blog_id = $_POST["blog_id"];
        if (delete_blog($blog_id)) {
            echo "Blog deleted successfully";
        } else {
            echo "Error: Could not delete blog";
        }
    }

    // Fetch the user's blogs
    $sql = "SELECT * FROM blogs WHERE creator_email = '$user_email' ORDER BY event_date ASC";
    $result = $conn->query($sql);

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
        <?php show_navbar(); ?>

        <h1>My Blogs</h1>

        <div>
            <table id="blogsTable" class="display">
                <thead>
                    <tr>
                        <th>Blog ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Creator Email</th>
                        <th>Privacy Filter</th>
                        <th>Event Date</th>
                        <th>Image</th>
                        <th>Actions</th>
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
                                echo "<td>" . htmlspecialchars($row["privacy_filter"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["event_date"]) . "</td>";

                                $image_path = "images/" . $row["blog_id"] . "/" . $row["blog_id"];
                                $default_image = "images/default_images/default-featured-image.jpg"; // Path to the default image

                                if (!file_exists($image_path)) {
                                    $image_path = $default_image;
                                }

                                echo "<td><img src='" . $image_path . "' alt='Image' width='100' height='100'></td>";
                                echo "<td>
                                        <a href='editBlogPage.php?blog_id=" . htmlspecialchars($row["blog_id"]) . "'>Edit</a>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='blog_id' value='" . htmlspecialchars($row["blog_id"]) . "'>
                                            <input type='submit' name='delete_blog' value='Delete'>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No blogs found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
    </body>
</html>