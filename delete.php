<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';

// Get parameters from URL and sanitize
$dbType = $_GET['db'] ?? '';
$id = intval($_GET['id'] ?? 0);

if (!$dbType || !$id) {
    die("Invalid request.");
}

// Decide database and table based on db parameter
if ($dbType === 'school') {
    $dbname = 'school_db';
    $table = 'tblstudentsdb';
} elseif ($dbType === 'student') {
    $dbname = 'student_db';
    $table = 'tblstudents';
} else {
    die("Unknown database type.");
}

// Connect to the selected database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute delete statement securely
$stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back to the students list page
header("Location: students.php");
exit;
?>
