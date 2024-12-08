<?php
session_start();
require 'db.php'; // Include database connection
require 'navbar.php'; // Include the navbar file

// Ensure the alphabet_book table exists
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS alphabet_book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    blog_id INT NOT NULL,
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE
)";
$conn->query($tableCreationQuery); // Execute table creation query

// Handle the addition to the Alphabet Book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addToAlphabetBook'])) {
    $selectedBlogs = $_POST['selectedBlogs'] ?? [];
    $userEmail = $_SESSION['user']['email']; // Assuming user is logged in and email is stored in session

    foreach ($selectedBlogs as $blogId) {
        $blogId = $conn->real_escape_string($blogId);
        $addQuery = "INSERT IGNORE INTO alphabet_book (user_email, blog_id) VALUES ('$userEmail', '$blogId')";
        $conn->query($addQuery);
    }
}

// Handle the removal from the Alphabet Book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeFromAlphabetBook'])) {
    $blogsToRemove = $_POST['selectedBlogsToRemove'] ?? [];
    $userEmail = $_SESSION['user']['email'];

    foreach ($blogsToRemove as $blogId) {
        $blogId = $conn->real_escape_string($blogId);
        $removeQuery = "DELETE FROM alphabet_book WHERE blog_id = '$blogId' AND user_email = '$userEmail'";
        $conn->query($removeQuery);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alphabet Book</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
</head>
<body>
    <h1>Photos ABCD</h1>
    <?php show_navbar(); // Display the navbar ?>

    <h2>Available Blogs</h2>
    <div>
        <label for="alphabetFilter" style="margin-right: 10px;">Filter by Alphabet:</label>
        <select id="alphabetFilter">
            <option value="">All</option>
            <?php
            foreach (range('A', 'Z') as $letter) {
                echo "<option value='$letter'>$letter</option>";
            }
            ?>
        </select>
    </div>

    <form method="POST" id="addForm">
        <input type="hidden" name="addToAlphabetBook" value="1">
        <table id="blogsTable" class="display">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>blog_id</th>
                    <th>title</th>
                    <th>description</th>
                    <th>creator_email</th>
                    <th>event_date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch blogs created by the logged-in user
                $userEmail = $_SESSION['user']['email'];
                $sql = "SELECT blog_id, title, description, creator_email, event_date 
                        FROM blogs 
                        WHERE creator_email = '$userEmail'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selectedBlogs[]' value='" . $row['blog_id'] . "'></td>";
                        echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                        echo "<td class='blogTitle'>" . htmlspecialchars($row["title"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["creator_email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["event_date"]) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <button type="submit">Add to Alphabet Book</button>
    </form>

    <h2>Alphabet Book Blogs</h2>
    <form method="POST" id="removeForm">
        <input type="hidden" name="removeFromAlphabetBook" value="1">
        <table id="alphabetBookTable" class="display">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>blog_id</th>
                    <th>title</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $email = $_SESSION['user']['email'];
                $alphabetBookQuery = "SELECT blogs.blog_id, blogs.title, blogs.description FROM alphabet_book 
                                      INNER JOIN blogs ON alphabet_book.blog_id = blogs.blog_id 
                                      WHERE alphabet_book.user_email = '$email'";
                $alphabetBookResult = $conn->query($alphabetBookQuery);
                $addedLetters = []; // Track unique first letters

                if ($alphabetBookResult->num_rows > 0) {
                    while ($row = $alphabetBookResult->fetch_assoc()) {
                        $firstLetter = strtoupper($row["title"][0]);
                        $addedLetters[$firstLetter] = true; // Add letter to tracker

                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selectedBlogsToRemove[]' value='" . $row['blog_id'] . "'></td>";
                        echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <button type="submit">Remove Selected Blogs</button>
    </form>

    <h2>Alphabet Book Completion Progress</h2>
    <div>
        <progress id="alphabetProgress" max="26" value="<?php echo count($addedLetters); ?>"></progress>
        <span id="progressText"><?php echo count($addedLetters); ?>/26 letters completed</span>
    </div>

    <h2>Table of Contents</h2>
    <ul>
        <?php
        $alphabetBookResult->data_seek(0); // Reset result pointer
        $pageNumber = 1;
        while ($row = $alphabetBookResult->fetch_assoc()) {
            echo "<li>Page $pageNumber: " . htmlspecialchars($row['title']) . "</li>";
            $pageNumber++;
        }
        ?>
    </ul>

    <?php
    $alphabetBookResult->data_seek(0); // Reset result pointer
    while ($row = $alphabetBookResult->fetch_assoc()) {
        echo '<div class="blog-page">';
        echo '<div class="blog-title">' . htmlspecialchars($row['title']) . '</div>';
        echo '<div class="blog-content">' . nl2br(htmlspecialchars($row['description'])) . '</div>';
        echo '</div>';
    }
    ?>

    <script>
        $(document).ready(function() {
            const table = $('#blogsTable').DataTable();
            const alphabetBookTable = $('#alphabetBookTable').DataTable();

            // Alphabet filter
            $('#alphabetFilter').on('change', function() {
                const selectedLetter = $(this).val();
                table.column(2).search(selectedLetter ? '^' + selectedLetter : '', true, false).draw();
            });
        });
    </script>
</body>
</html>
