<?php
include 'show-button.php';

function show_navbar() {
    echo '<div class="navbar">
                      <a href="homepage.php" >Home</a>
                  	<a href="loginpage.php">Login</a>
                  	<a href="logoutpage.php">Logout</a>
                  	<a href="registerpage.php">Register</a>
                  	<a href="viewblogs.php">View Blogs</a>
                  	<a href="newblogcreation.php">Create Blog</a>';

		//code from learnandhelp example, may be used later
      /*if (isset($_SESSION['email'])) {
        if ($_SESSION['role'] == 'admin') {
          echo '<a href="administration.php">Administration</a>';
        }
        echo '<a href="registration_form.php" id="register">Enroll Now</a>';
      }elseif(isset($_SESSION['email']) == false){
        echo '<a href="login.php" id="register">Enroll Now</a>';
      } */
       echo '</div>';

}

?>