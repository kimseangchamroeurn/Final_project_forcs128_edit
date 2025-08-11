<?php
$host = "localhost";
$user = "root"; // or your MySQL user
$pass = ""; // or your MySQL password
$db = "student_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>