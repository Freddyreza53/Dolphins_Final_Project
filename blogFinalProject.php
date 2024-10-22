<!DOCTYPE html>
<html>
    <head>
        <title>Blog Website</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>
        <h1>Blogs</h1>
        <!-- Blog Form -->
        <form method="POST" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>

            <label for="creator_email">Creator Email:</label>
            <input type="email" id="creator_email" name="creator_email" required><br>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required><br>

            <input type="submit" name="submit" value="Add Blog">
        </form>

        <h1>Login page here</h1>

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
                    // MySQL database info
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "photos_d_db";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Handle insert request
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
                        $title = $conn->real_escape_string($_POST["title"]);
                        $description = $conn->real_escape_string($_POST["description"]);
                        $creator_email = $conn->real_escape_string($_POST["creator_email"]);
                        $event_date = $conn->real_escape_string($_POST["event_date"]);

                        $insert_sql = "INSERT INTO blogs (title, description, creator_email, event_date) 
                                    VALUES ('$title', '$description', '$creator_email', '$event_date')";

                        if ($conn->query($insert_sql) === TRUE) {
                            echo "New blog added successfully";
                        } else {
                            echo "Error: " . $insert_sql . "<br>" . $conn->error;
                        }
                    }

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