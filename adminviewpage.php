<?php
    session_start();
    require 'db.php'; // Include database connection
    require 'adminCheck.php';
    include 'navbar.php'; 

    // Handle delete request for blogs
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_blog"])) {
        $blog_id = $conn->real_escape_string($_POST["blog_id"]);
        $delete_sql = "DELETE FROM blogs WHERE blog_id = '$blog_id'";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Blog deleted successfully";
        } else {
            echo "Error: " . $delete_sql . "<br>" . $conn->error;
        }
    }

    // Handle delete request for users
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
        $email = $conn->real_escape_string($_POST["email"]);
        $delete_sql = "DELETE FROM users WHERE email = '$email'";
        if ($conn->query($delete_sql) === TRUE) {
            echo "User deleted successfully";
        } else {
            echo "Error: " . $delete_sql . "<br>" . $conn->error;
        }
    }

    // Determine which data to display
    $type = isset($_GET['type']) ? $_GET['type'] : 'blogs';
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

        <?php show_navbar(); ?>
        <?php if ($type == 'blogs'): ?>
            <h1>Blogs</h1>
        <?php else: ?>
            <h1>Users</h1>
        <?php endif; ?>
        
        <div>
            <a href="adminviewpage.php?type=blogs">Blogs</a>
            <a href="adminviewpage.php?type=users">Users</a>
        </div>

        <table id="admin_table">
            <thead>
                <tr>
                    <?php if ($type == 'blogs'): ?>
                        <th>Blog ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Creator Email</th>
                        <th>Event Date</th>
                        <th>Actions</th>
                    <?php else: ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
                <tbody>
                    <?php
                    if ($type == 'blogs') {
                        $sql = "SELECT blog_id, title, description, creator_email, event_date FROM blogs";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["creator_email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["event_date"]) . "</td>";
                                echo "<td>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='blog_id' value='" . htmlspecialchars($row["blog_id"]) . "'>
                                            <input type='submit' name='delete_blog' value='Delete'>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        }
                    } else {
                        $sql = "SELECT first_name, last_name, email, role FROM users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                                echo "<td>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='email' value='" . htmlspecialchars($row["email"]) . "'>
                                            <input type='submit' name='delete_user' value='Delete'>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>