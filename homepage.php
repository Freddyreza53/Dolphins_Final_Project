<?php
    session_start();
    require 'db.php'; // Include database connection

    if ($_SESSION['user']) {
        $user = $_SESSION['user'];
        $sql = "SELECT * FROM blogs WHERE privacy_filter = 'private' ORDER BY event_date ASC";
    } else {
        $sql = "SELECT * FROM blogs WHERE privacy_filter = 'public' ORDER BY event_date ASC";
    }

    
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

        <h1>Welcome to Photos ABCD</h1>
        <p> Created by Team Dolphins </p>

        <div class="navbar">
            <a href="homepage.php" >Home</a>
        	<a href="loginpage.php">Login</a>
        	<a href="logoutpage.php">Logout</a>
        	<a href="registerpage.php">Register</a>
        	<a href="viewblogs.php">View Blogs</a>
        	<a href="newblogcreation.php">Create Blog</a>
        </div>

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
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["creator_email"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["event_date"]) . "</td>";
                            echo "<td><a href='images/" . $row["blog_id"] . "/" . $row["blog_id"] ."'>View</a></td>";
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