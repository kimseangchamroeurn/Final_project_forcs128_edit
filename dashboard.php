<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';

// Connect to school_db
$conn1 = new mysqli($host, $user, $password, 'school_db');
if ($conn1->connect_error) {
    die("Connection failed (school_db): " . $conn1->connect_error);
}

// Connect to student_db
$conn2 = new mysqli($host, $user, $password, 'student_db');
if ($conn2->connect_error) {
    die("Connection failed (student_db): " . $conn2->connect_error);
}

// Fetch top students from school_db
$sql1 = "SELECT id, Firstname, Lastname, gender, class, student_rank FROM tblstudentsdb ORDER BY student_rank ASC LIMIT 10";
$result1 = $conn1->query($sql1) or die("Query failed on school_db: " . $conn1->error);

// Fetch top students from student_db
$sql2 = "SELECT id, name, gender, class, student_rank FROM tblstudents ORDER BY student_rank ASC LIMIT 10";
$result2 = $conn2->query($sql2) or die("Query failed on student_db: " . $conn2->error);

// Count total students
$total_school = $conn1->query("SELECT COUNT(*) as cnt FROM tblstudentsdb")->fetch_assoc()['cnt'];
$total_student = $conn2->query("SELECT COUNT(*) as cnt FROM tblstudents")->fetch_assoc()['cnt'];
$total_students = $total_school + $total_student;

// Merge both results
$students = [];
while ($row = $result1->fetch_assoc()) {
    $students[] = [
        'firstname' => $row['Firstname'],
        'lastname' => $row['Lastname'],
        'gender' => $row['gender'],
        'class' => $row['class'],
        'rank' => $row['student_rank']
    ];
}
while ($row = $result2->fetch_assoc()) {
    $parts = explode(' ', $row['name'], 2);
    $students[] = [
        'firstname' => $parts[0],
        'lastname' => $parts[1] ?? '',
        'gender' => $row['gender'],
        'class' => $row['class'],
        'rank' => $row['student_rank']
    ];
}

// Sort by rank & take top 4
usort($students, fn($a, $b) => $a['rank'] <=> $b['rank']);
$top_students = array_slice($students, 0, 4);

$conn1->close();
$conn2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Paragon University - Top Students</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="cssStyleBackEnd.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Paragon University</a>
    </div>
   <ul class="nav navbar-nav">
  <li><a href="home.php">Home</a></li>
  <li class="active"><a href="dashboard.php" style="color: white;">Dashboard</a></li>
  <li><a href="newsfeed.php">News Feed</a></li>
  <li><a href="students.php">Students</a></li>
</ul>

  </div>
</nav>

<!-- Welcome Section -->
<div class="container">
    <div class="welcome-section">
        <h1>Welcome to Paragon University</h1>
        <p>Shaping the leaders of tomorrow.</p>
    </div>

    <!-- Top Students Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <span>Top 4 Students</span>
            <div>
                <span class="badge">Total Students: <?= $total_students ?></span>
                <a href="students.php" class="btn btn-primary btn-sm">View More</a>
            </div>
        </div>
        <div class="panel-body">
            <?php if (count($top_students) === 0): ?>
                <p>No students found.</p>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_students as $i => $student): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($student['firstname']) ?></td>
                                <td><?= htmlspecialchars($student['lastname']) ?></td>
                                <td><?= htmlspecialchars($student['gender']) ?></td>
                                <td><?= htmlspecialchars($student['class']) ?></td>
                                <td><?= htmlspecialchars($student['rank']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; Paragon University <?= date('Y') ?>
    </footer>
</div>

</body>
</html>
