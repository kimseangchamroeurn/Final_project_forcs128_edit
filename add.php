<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';

$dbType = $_GET['db'] ?? 'school'; // default to school_db

// Determine database and table based on db parameter
if ($dbType === 'school') {
    $dbname = 'school_db';
    $table = 'tblstudentsdb';
} elseif ($dbType === 'student') {
    $dbname = 'student_db';
    $table = 'tblstudents';
} else {
    die("Unknown database.");
}

// Connect to the chosen database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $class = intval($_POST['class'] ?? 0);
    $student_rank = intval($_POST['student_rank'] ?? 0);

    if ($dbname === 'student_db') {
        // In student_db, we have only 'name' (full name), so combine first+last
        $name = $firstname . ($lastname ? ' ' . $lastname : '');
    }

    // Basic validation
    if (!$firstname || !$gender || !$class || !$student_rank) {
        $error = "Please fill in all required fields.";
    } else {
        if ($dbname === 'school_db') {
            $stmt = $conn->prepare("INSERT INTO $table (Firstname, Lastname, gender, class, student_rank) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $firstname, $lastname, $gender, $class, $student_rank);
        } else {
            $stmt = $conn->prepare("INSERT INTO $table (name, gender, class, student_rank) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $name, $gender, $class, $student_rank);
        }

        if ($stmt->execute()) {
            header("Location: students.php");
            exit;
        } else {
            $error = "Insert failed: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student - The University</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; background: white; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Student to <?php echo htmlspecialchars($dbname); ?></h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <?php if ($dbname === 'school_db'): ?>
            <div class="form-group">
                <label>Firstname</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Lastname</label>
                <input type="text" name="lastname" class="form-control">
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="firstname" class="form-control" required placeholder="Enter full name">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Class</label>
            <input type="number" name="class" class="form-control" required min="1" max="12">
        </div>

        <div class="form-group">
            <label>Rank</label>
            <input type="number" name="student_rank" class="form-control" required min="1">
        </div>

        <button type="submit" class="btn btn-success">Add Student</button>
        <a href="students.php" class="btn btn-default">Cancel</a>
    </form>
</div>
</body>
</html>
