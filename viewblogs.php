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

        <h1>Photos ABCD</h1>

        <?php include 'navbar.php'; ?>
        <?php show_navbar(); ?>

        <h1>Blogs</h1>

<!-- Alphabet Filter Dropdown -->
<label for="alphabetFilter">Filter by Alphabet:</label>
<select id="alphabetFilter">
    <option value="">All</option>
    <?php
        foreach (range('A', 'Z') as $letter) {
            echo "<option value='$letter'>$letter</option>";
        }
    ?>
</select>

<!-- Progress Tracker -->
<div>
    <h3>Alphabet Book Completion Progress</h3>
    <progress id="alphabetProgress" max="26" value="0"></progress>
    <span id="progressText"></span>
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

            // Fetch blog data from the database
            $sql = "SELECT blog_id, title, description, creator_email, event_date FROM blogs";
            $result = $conn->query($sql);
            $alphabetTracker = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $firstLetter = strtoupper($row["title"][0]);
                    if (!in_array($firstLetter, $alphabetTracker)) {
                        $alphabetTracker[] = $firstLetter;
                    }
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["blog_id"]) . "</td>";
                    echo "<td class='blogTitle'>" . htmlspecialchars($row["title"]) . "</td>";
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

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#blogsTable').DataTable();

        // Alphabet Filter
        $('#alphabetFilter').on('change', function() {
            const selectedLetter = $(this).val();
            table.column(1).search(selectedLetter ? '^' + selectedLetter : '', true, false).draw();
        });

        // Progress Tracker Update
        const alphabetProgress = $('#alphabetProgress');
        const progressText = $('#progressText');
        const filledLetters = <?php echo json_encode($alphabetTracker); ?>;
        alphabetProgress.attr('value', filledLetters.length);
        progressText.text(filledLetters.length + "/26 letters completed");
    });
</script>
</body>
</html>