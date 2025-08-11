<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';

// Get db and id parameters
$dbType = $_GET['db'] ?? '';
$id = intval($_GET['id'] ?? 0);

if (!$dbType || !$id) {
    die("Invalid request.");
}

// Select database and table based on $dbType
if ($dbType === 'school') {
    $dbname = 'school_db';
    $table = 'tblstudentsdb';
} elseif ($dbType === 'student') {
    $dbname = 'student_db';
    $table = 'tblstudents';
} else {
    die("Unknown database.");
}

// Connect to the selected database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $class = intval($_POST['class'] ?? 0);
    $rank = intval($_POST['rank'] ?? 0);

    if ($firstname === '') $errors[] = 'Firstname is required.';
    if ($gender === '') $errors[] = 'Gender is required.';
    if ($class <= 0) $errors[] = 'Class must be positive number.';
    if ($rank <= 0) $errors[] = 'Rank must be positive number.';

    if (empty($errors)) {
        // Prepare UPDATE based on database type
        if ($dbType === 'school') {
            $stmt = $conn->prepare("UPDATE $table SET Firstname=?, Lastname=?, gender=?, class=?, student_rank=? WHERE id=?");
            $stmt->bind_param("sssiii", $firstname, $lastname, $gender, $class, $rank, $id);
        } else {
            // For student_db.tblstudents, name is a single string of full name
            $fullname = $firstname . ($lastname ? " $lastname" : "");
            $stmt = $conn->prepare("UPDATE $table SET name=?, gender=?, class=?, student_rank=? WHERE id=?");
            $stmt->bind_param("ssiii", $fullname, $gender, $class, $rank, $id);
        }

        if ($stmt->execute()) {
            $success = "Student updated successfully.";
        } else {
            $errors[] = "Update failed: " . $conn->error;
        }

        $stmt->close();
    }
}

// Fetch existing student data to show in form
$sql = "SELECT * FROM $table WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();

// Prepare form values depending on dbType
if ($dbType === 'school') {
    $firstname_val = htmlspecialchars($student['Firstname']);
    $lastname_val = htmlspecialchars($student['Lastname']);
    $gender_val = htmlspecialchars($student['gender']);
    $class_val = $student['class'];
    $rank_val = $student['student_rank'];
} else {
    // split name into first and last (if possible)
    $name = htmlspecialchars($student['name']);
    $firstname_val = $name;
    $lastname_val = '';
    if (strpos($name, ' ') !== false) {
        $parts = explode(' ', $name, 2);
        $firstname_val = $parts[0];
        $lastname_val = $parts[1];
    }
    $gender_val = htmlspecialchars($student['gender']);
    $class_val = $student['class'];
    $rank_val = $student['student_rank'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }
        .error { color: red; }
        .success { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Student</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
            <br><br>
            <a href="students.php" class="btn btn-default">Back to Students</a>
        </div>
    <?php else: ?>

    <form method="POST">
        <div class="form-group">
            <label>Firstname</label>
            <input type="text" name="firstname" class="form-control" value="<?= $firstname_val ?>" required>
        </div>
        <div class="form-group">
            <label>Lastname</label>
            <input type="text" name="lastname" class="form-control" value="<?= $lastname_val ?>">
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">-- Select Gender --</option>
                <option value="Male" <?= $gender_val === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $gender_val === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $gender_val === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Class</label>
            <input type="number" name="class" class="form-control" value="<?= $class_val ?>" min="1" required>
        </div>
        <div class="form-group">
            <label>Rank</label>
            <input type="number" name="rank" class="form-control" value="<?= $rank_val ?>" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="students.php" class="btn btn-default">Cancel</a>
    </form>

    <?php endif; ?>
</div>
</body>
</html>
