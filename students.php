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

// Query tblstudentsdb from school_db
$sql1 = "SELECT id, Firstname, Lastname, gender, class, student_rank FROM tblstudentsdb";
$result1 = $conn1->query($sql1);
if (!$result1) {
    die("Query failed on school_db: " . $conn1->error);
}

// Query tblstudents from student_db
$sql2 = "SELECT id, name, gender, class, student_rank FROM tblstudents";
$result2 = $conn2->query($sql2);
if (!$result2) {
    die("Query failed on student_db: " . $conn2->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Students - Paragon University</title>
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
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="newsfeed.php">News Feed</a></li>
      <li class="active"><a href="students.php">Students</a></li>
    </ul>
  </div>
</nav>


<!-- Welcome Section -->
<div class="container">
  <div class="welcome-section">
    <h1>Welcome to Paragon University</h1>
    <p>Shaping the leaders of tomorrow.</p>
  </div>

  <!-- Students Panel -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <span>All Students</span>
      <div>
        <span class="badge"><?php echo $result1->num_rows + $result2->num_rows; ?></span>
        <a href="add.php" class="btn btn-success btn-sm">Add Student</a>
      </div>
    </div>

    <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Gender</th>
            <th>Class</th>
            <th>Rank</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;

          if ($result1->num_rows > 0) {
              while ($row = $result1->fetch_assoc()) {
                  echo "<tr>
                      <td>{$i}</td>
                      <td>" . htmlspecialchars($row['Firstname']) . "</td>
                      <td>" . htmlspecialchars($row['Lastname']) . "</td>
                      <td>" . htmlspecialchars($row['gender']) . "</td>
                      <td>" . htmlspecialchars($row['class']) . "</td>
                      <td>" . htmlspecialchars($row['student_rank']) . "</td>
                      <td>
                          <a href='edit.php?db=school&id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                          <a href='delete.php?db=school&id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this student?\")'>Delete</a>
                      </td>
                  </tr>";
                  $i++;
              }
          }

          if ($result2->num_rows > 0) {
              while ($row = $result2->fetch_assoc()) {
                  $fullname = htmlspecialchars($row['name']);
                  $firstname = $fullname;
                  $lastname = '';
                  if (strpos($fullname, ' ') !== false) {
                      $parts = explode(' ', $fullname, 2);
                      $firstname = $parts[0];
                      $lastname = $parts[1];
                  }

                  echo "<tr>
                      <td>{$i}</td>
                      <td>{$firstname}</td>
                      <td>{$lastname}</td>
                      <td>" . htmlspecialchars($row['gender']) . "</td>
                      <td>" . htmlspecialchars($row['class']) . "</td>
                      <td>" . htmlspecialchars($row['student_rank']) . "</td>
                      <td>
                          <a href='edit.php?db=student&id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                          <a href='delete.php?db=student&id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this student?\")'>Delete</a>
                      </td>
                  </tr>";
                  $i++;
              }
          }

          if ($i === 1) {
              echo '<tr><td colspan="7">No students found.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>&copy; Paragon University <?= date('Y') ?></footer>
</div>

</body>
</html>

<?php
$conn1->close();
$conn2->close();
?>
