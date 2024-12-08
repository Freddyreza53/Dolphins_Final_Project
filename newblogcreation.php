<?php
session_start();
require 'db.php'; // Include database connection
include 'navbar.php'; // Include navigation bar

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    echo "Error: User is not logged in.";
    exit();
}

// Handle insert request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $creator_email = $conn->real_escape_string($_SESSION['email']);
    $event_date = $conn->real_escape_string($_POST["event_date"]);
    $privacy_filter = $conn->real_escape_string($_POST["privacy_filter"]);

    $insert_sql = "INSERT INTO blogs (title, description, creator_email, event_date, privacy_filter)
                VALUES ('$title', '$description', '$creator_email', '$event_date', '$privacy_filter')";

    if ($conn->query($insert_sql) === TRUE) {
        $blog_id = $conn->insert_id; // Get the ID of the newly created blog post

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

            $image_dir = "images/$blog_id";
            if (!is_dir($image_dir)) {
                mkdir($image_dir, 0777, true);
            } else {
                echo "Directory already exists: $image_dir<br>";
            }

            $image_path = $image_dir . '/' . $blog_id;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        header("Location: myBlogsPage.php");
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
}
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
    <?php show_navbar() // Display the navbar?>
    
    <h1>Create New Blog</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" required><br>

        <input type="radio" id="public" name="privacy_filter" value="public" <?php echo ($blog['privacy_filter'] == 'public') ? 'checked' : ''; ?>>
        <label for="privacy_filter">Public</label>
        
        <input type="radio" id="private" name="privacy_filter" value="private" <?php echo ($blog['privacy_filter'] == 'private') ? 'checked' : ''; ?>>
        <label for="privacy_filter">Private</label><br>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" accept="image/*"><br>

        <input type="submit" name="submit" value="Add Blog">
    </form>
</body>
</html>