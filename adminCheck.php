<?php
// this will sta
    session_start();
    require 'db.php';

    // Function to check if the logged-in user is an admin
    function isAdmin() {
        if (isset($_SESSION['user'])) {
            global $conn;
            // Prepare a statement to fetch the user's role from the database
            $stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['user']['email']);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            // Return true if the user's role is 'admin', otherwise false
            return $role === 'admin';
        }
        return false;
    }
?>