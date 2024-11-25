<?php
session_start();

function show_navbar() {
  echo '<div class="navbar">
            <a href="homepage.php">Home</a>';

    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        echo '<a href="logoutpage.php">Logout</a>';
        echo '<a href="viewblogs.php">Alphabet Book</a>';
        echo '<a href="newblogcreation.php">Create Blog</a>';
        echo '<a href="userview.php">My Blogs</a>';

        if ($_SESSION['role'] == 'admin') {
            echo '<a href="adminviewpage.php">Administration</a>';
        }
    } else {
        echo '<a href="loginpage.php">Login</a>';
        echo '<a href="registerpage.php">Register</a>';
    }

       echo '</div>';

}

?>