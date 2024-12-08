<?php

function show_navbar() {
  echo '<div class="navbar">
            <a href="homepage.php">Home</a>';

    // Check if the user is logged in
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        echo '<a href="logoutpage.php">Logout</a>';
        echo '<a href="alphabetbook.php">Alphabet Book</a>';
        echo '<a href="newblogcreation.php">Create Blog</a>';
        echo '<a href="myBlogsPage.php">My Blogs</a>';
        // Check if the user is an admin
        if ($_SESSION['role'] == 'admin') {
            echo '<a href="adminviewpage.php">Admin</a>';
        }
        
        echo '<p>Welcome, ' . htmlspecialchars($_SESSION['first_name']) . '!</p>';
    } else {
        echo '<a href="loginpage.php">Login</a>';
        echo '<a href="registerpage.php">Register</a>';
    }

       echo '</div>';

}

?>