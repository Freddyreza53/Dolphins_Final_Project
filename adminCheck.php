<?php
    session_start();
    require 'db.php';

    function isAdmin() {
        if (isset($_SESSION['user'])) {
            global $conn;
            $stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['user']['email']);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            return $role === 'admin';
        }
        return false;
    }
?>