<?php
require 'db.php'; // Include database connection

function delete_blog($blog_id) {
    global $conn;
    $blog_id = $conn->real_escape_string($blog_id);
    $delete_sql = "DELETE FROM blogs WHERE blog_id = '$blog_id'";
    return $conn->query($delete_sql);
}

function delete_user($email) {
    global $conn;
    $email = $conn->real_escape_string($email);
    $delete_sql = "DELETE FROM users WHERE email = '$email'";
    return $conn->query($delete_sql);
}

function update_blog($blog_id, $title, $description, $event_date, $privacy_filter) {
    global $conn;
    $blog_id = $conn->real_escape_string($blog_id);
    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);
    $event_date = $conn->real_escape_string($event_date);
    $privacy_filter = $conn->real_escape_string($privacy_filter);

    $update_sql = "UPDATE blogs SET title = '$title', description = '$description', event_date = '$event_date', privacy_filter = '$privacy_filter' WHERE blog_id = '$blog_id'";
    return $conn->query($update_sql);
}
?>