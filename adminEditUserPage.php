<?php
    session_start();
    require 'db.php'; // Include database connection
    include 'navbar.php'; 

    // Ensure the user is logged in
    if (!isset($_SESSION['email'])) {
        echo "Error: User is not logged in.";
        exit();
    }

    // Get the user email from the query parameter
    $user_email = $_GET['email'];

    // Fetch the user details
    $sql = "SELECT * FROM users WHERE email = '$user_email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "Error: User not found.";
        exit();
    }

    $user = $result->fetch_assoc();

    // Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $first_name = $conn->real_escape_string($_POST["first_name"]);
    $last_name = $conn->real_escape_string($_POST["last_name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $role = $conn->real_escape_string($_POST["role"]);

    $update_sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', role = '$role' WHERE email = '$user_email'";

    if ($conn->query($update_sql) === TRUE) {
        echo "User updated successfully.";
        header("Location: adminviewpage.php?type=users");
        exit();
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" charset="utf8" src="scripts.js"></script>
</head>
    <body>
        <h1>Edit User</h1>
        <?php show_navbar(); ?>

        <form method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

            <label for="role">Role:</label>
            <input type="radio" id="blogger" name="role" value="blogger" <?php echo ($user['role'] == 'blogger') ? 'checked' : ''; ?>>
            <label for="blogger">Blogger</label>
            <input type="radio" id="admin" name="role" value="admin" <?php echo ($user['role'] == 'admin') ? 'checked' : ''; ?>>
            <label for="admin">Admin</label><br>

            <input type="submit" name="update" value="Update User">
        </form>
    </body>
</html>