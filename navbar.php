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
  

		//code for checking if other options should be added
		//to be fine-tuned soon
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