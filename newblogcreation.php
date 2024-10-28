<!DOCTYPE html>
<html>
    <head>
        <title>New Blog</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="scripts.js"></script>
    </head>
    <body>

        <a href="homepage.php" >View Blogs</a>

        <h1>Create Blog</h1>
        <!-- Blog Form -->
        <form method="POST" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" pattern="[\w]*"
            title="Blog Title can't contain a symbol" required><br>
            <!-- Fix regex later to allow symbols after first character-->

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>

            <label for="creator_email">Creator Email:</label>
            <input type="email" id="creator_email" name="creator_email" required><br>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required><br>

            <input type="submit" name="submit" value="Add Blog">
        </form>

        <h2>To View Blogs</h2>
        <a href="viewblogs.php" >View Blogs</a>

        <div>
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

                    // Close connection
                    $conn->close();
                    ?>
        </div>
    </body>
</html>