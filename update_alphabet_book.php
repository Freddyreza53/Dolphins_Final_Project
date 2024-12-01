<?php
session_start();
require 'db.php';

$action = $_POST['action'];
$blogs = $_POST['blogs'] ?? [];
$userEmail = $_SESSION['user']['email'];

if ($action === 'add') {
    foreach ($blogs as $blogId) {
        $blogId = $conn->real_escape_string($blogId);
        $addQuery = "INSERT IGNORE INTO alphabet_book (user_email, blog_id) VALUES ('$userEmail', '$blogId')";
        $conn->query($addQuery);
    }
    echo "Selected blogs have been added to the Alphabet Book.";
} elseif ($action === 'remove') {
    foreach ($blogs as $blogId) {
        $blogId = $conn->real_escape_string($blogId);
        $removeQuery = "DELETE FROM alphabet_book WHERE blog_id = '$blogId' AND user_email = '$userEmail'";
        $conn->query($removeQuery);
    }
    echo "Selected blogs have been removed from the Alphabet Book.";
}
?>
