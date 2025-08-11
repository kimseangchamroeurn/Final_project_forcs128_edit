<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';
$dbname = 'news_db';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_date = $_POST['news_date'] ?? '';
    $news_content = $_POST['news_content'] ?? '';

    if (!$news_date || !$news_content) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO newsfeed (news_date, news_content) VALUES (?, ?)");
        $stmt->bind_param("ss", $news_date, $news_content);
        if ($stmt->execute()) {
            header("Location: newsfeed.php");
            exit;
        } else {
            $error = "Failed to add news.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add News - The University</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style> body { background-color: #f4f4f4; } .container { margin-top: 20px; } footer { margin-top: 40px; padding: 10px; background-color: #222; color: #fff; } </style>
</head>
<body>
<div class="container">
    <h3>Add News</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="news_date">Date:</label>
            <input type="date" id="news_date" name="news_date" class="form-control" required value="<?= htmlspecialchars($_POST['news_date'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="news_content">Content:</label>
            <textarea id="news_content" name="news_content" class="form-control" rows="5" required><?= htmlspecialchars($_POST['news_content'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add News</button>
        <a href="newsfeed.php" class="btn btn-default">Cancel</a>
    </form>
</div>
<footer class="text-center">&copy; University 2025</footer>
</body>
</html>
<?php $conn->close(); ?>
