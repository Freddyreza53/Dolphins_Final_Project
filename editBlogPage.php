<?php
    session_start();
    require 'db.php'; // Include database connection
    include 'navbar.php'; // Include navigation bar

    // Ensure the user is logged in
    if (!isset($_SESSION['email'])) {
        echo "Error: User is not logged in.";
        exit();
    }

    // Get the blog ID from the query parameter
    $blog_id = intval($_GET['blog_id']);

    // Fetch the blog details
    $sql = "SELECT * FROM blogs WHERE blog_id = '$blog_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "Error: Blog not found.";
        exit();
    }

    $blog = $result->fetch_assoc();

    // Handle update request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
        $title = $conn->real_escape_string($_POST["title"]);
        $description = $conn->real_escape_string($_POST["description"]);
        $event_date = $conn->real_escape_string($_POST["event_date"]);
        $privacy_filter = $conn->real_escape_string($_POST["privacy_filter"]);

        $update_sql = "UPDATE blogs SET title = '$title', description = '$description', event_date = '$event_date', privacy_filter = '$privacy_filter' WHERE blog_id = '$blog_id'";

        if ($conn->query($update_sql) === TRUE) {
            // Handle image update
            if (isset($_POST['remove_image']) && $_POST['remove_image'] == 'yes') {
                echo "Removing image<br>";
                echo "images/$blog_id<br>";

                $image_dir = "images/$blog_id";
                array_map('unlink', glob("$image_dir/*"));
                rmdir($image_dir);
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_dir = "images/$blog_id";
                if (!is_dir($image_dir)) {
                    mkdir($image_dir, 0777, true);
                }

                $image_path = $image_dir . '/' . $blog_id;
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            header("Location: myBlogsPage.php");
            exit();
        } else {
            echo "Error: " . $update_sql . "<br>" . $conn->error;
        }
    }

    // Check if the blog has an existing image
    $image_dir = "images/$blog_id";
    $image_path = $image_dir . '/' . $blog_id;
    $image_exists = file_exists($image_path);
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
    <?php show_navbar(); // Display the navbar?>

    <h1>Edit Blog</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($blog['description']); ?></textarea><br>

        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($blog['event_date']); ?>" required><br>

        <label for="privacy_filter">Privacy:</label>
        <input type="radio" id="public" name="privacy_filter" value="public" <?php echo ($blog['privacy_filter'] == 'public') ? 'checked' : ''; ?>>
        <label for="public">Public</label>
        <input type="radio" id="private" name="privacy_filter" value="private" <?php echo ($blog['privacy_filter'] == 'private') ? 'checked' : ''; ?>>
        <label for="private">Private</label><br>

        <?php if ($image_exists): ?>
            <label for="remove_image">Remove Existing Image:</label>
            <input type="checkbox" id="remove_image" name="remove_image" value="yes"><br>
            <img src="<?php echo $image_path; ?>" alt="Existing Image" width="100" height="100"><br>
        <?php else: ?>
            <label for="image">Upload New Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br>
        <?php endif; ?>

        <input type="submit" name="update" value="Update Blog">
    </form>
</body>
</html>