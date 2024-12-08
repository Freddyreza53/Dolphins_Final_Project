<?php
    //session_start();
    require 'dbCommonRequests.php'; // Include common database operations
    require 'adminCheck.php'; // Include admin check
    include 'navbar.php'; // Include navigation bar

    // Handle delete request for blogs
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_blog"])) {
        $blog_id = $_POST["blog_id"];
        if (delete_blog($blog_id)) {
            echo "Blog deleted successfully";
        } else {
            echo "Error: Could not delete blog";
        }
    }

    // Handle delete request for users
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
        $email = $_POST["email"];
        if (delete_user($email)) {
            echo "User deleted successfully";
        } else {
            echo "Error: Could not delete user";
        }
    }

    // Determine which data to display (blogs or users)
    $type = isset($_GET['type']) ? $_GET['type'] : 'blogs';

    // Fetch data based on the type
    if ($type == 'blogs') {
        $sql = "SELECT * FROM blogs ORDER BY event_date ASC";
    } else {
        $sql = "SELECT users.email, users.first_name, users.last_name, users.role, COUNT(blogs.blog_id) AS blog_count
            FROM users
            LEFT JOIN blogs ON users.email = blogs.creator_email
            GROUP BY users.email, users.first_name, users.last_name, users.role
            ORDER BY users.email ASC";
    }

    $result = $conn->query($sql);
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

        <h1>Photos ABCD</h1>

        <?php show_navbar(); // Display navbar?>
        <?php if ($type == 'blogs'): ?>
            <h1>Blogs</h1>
        <?php else: ?>
            <h1>Users</h1>
        <?php endif; ?>
        
        <div>
            <a href="adminviewpage.php?type=blogs">Blogs</a>
            <a href="adminviewpage.php?type=users">Users</a>
        </div>

        <table id="blogsTable" class="display">
            <thead>
                <tr>
                    <?php if ($type == 'blogs'): ?>
                        <th>Blog ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Creator Email</th>
                        <th>Privacy Filter</th>
                        <th>Event Date</th>
                        <th>Image</th>
                        <th>Actions</th>
                    <?php else: ?>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Blog Count</th>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            // Display data based on the type
                            if ($type == 'blogs') {
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
                                        <form method='GET' action='adminEditBlogPage.php' style='display:inline;'>
                                            <input type='hidden' name='blog_id' value='" . htmlspecialchars($row["blog_id"]) . "'>
                                            <input type='submit' value='Edit' class='button'>
                                        </form>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='blog_id' value='" . htmlspecialchars($row["blog_id"]) . "'>
                                            <input type='submit' name='delete_blog' value='Delete' class='button delete'>
                                        </form>
                                      </td>";
                            } else {
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["blog_count"]) . "</td>";
                                echo "<td>
                                        <form method='GET' action='adminEditUserPage.php' style='display:inline;'>
                                            <input type='hidden' name='email' value='" . htmlspecialchars($row["email"]) . "'>
                                            <input type='submit' value='Edit' class='button'>
                                        </form>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='email' value='" . htmlspecialchars($row["email"]) . "'>
                                            <input type='submit' name='delete_user' value='Delete' class='button delete'>
                                        </form>
                                      </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>