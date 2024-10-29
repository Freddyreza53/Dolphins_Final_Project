<!DOCTYPE html>
<html>
    <head>
        <title>View Blogs</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <a href="homepage.php" >Home</a>

        <h1>Blogs</h1>

        <h2>To Create Blogs</h2>
        <a href="newblogcreation.php" >Create Blog</a>

        <div>
            <table id="blogsTable" class="display">
                <thead>
                    <tr>
                        <th>blog_id</th>
                        <th>title</th>
                        <th>description</th>
                        <th>creator_email</th>
                        <th>event_date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'db.php'; // Include database connection

                    // Handle delete request
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
                        $blog_id = $conn->real_escape_string($_POST["blog_id"]);

                        $delete_sql = "DELETE FROM blogs WHERE blog_id = '$blog_id'";

                        if ($conn->query($delete_sql) === TRUE) {
                            echo "Blog deleted successfully";
                        } else {
                            echo "Error: " . $delete_sql . "<br>" . $conn->error;
                        }
                    }

                    // FETCH blog data from the database
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
                                        <input type='submit' name='delete' value='Delete'>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    }
                    // Close connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>