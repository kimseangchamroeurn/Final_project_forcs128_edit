<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';
$dbname = 'news_db';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: newsfeed.php");
    exit;
}

$error = '';
// Fetch current news
$stmt = $conn->prepare("SELECT news_date, news_content FROM newsfeed WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($news_date, $news_content);
if (!$stmt->fetch()) {
    $stmt->close();
    $conn->close();
    header("Location: newsfeed.php");
    exit;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_date_new = $_POST['news_date'] ?? '';
    $news_content_new = $_POST['news_content'] ?? '';

    if (!$news_date_new || !$news_content_new) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("UPDATE newsfeed SET news_date = ?, news_content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $news_date_new, $news_content_new, $id);
        if ($stmt->execute()) {
            header("Location: newsfeed.php");
            exit;
        } else {
            $error = "Failed to update news.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit News - The University</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style> body { background-color: #f4f4f4; } .container { margin-top: 20px; } footer { margin-top: 40px; padding: 10px; background-color: #222; color: #fff; } </style>
</head>
<body>
<div class="container">
    <h3>Edit News</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="news_date">Date:</label>
            <input type="date" id="news_date" name="news_date" class="form-control" required value="<?= htmlspecialchars($_POST['news_date'] ?? $news_date) ?>">
        </div>
        <div class="form-group">
            <label for="news_content">Content:</label>
            <textarea id="news_content" name="news_content" class="form-control" rows="5" required><?= htmlspecialchars($_POST['news_content'] ?? $news_content) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update News</button>
        <a href="newsfeed.php" class="btn btn-default">Cancel</a>
    </form>
</div>
<footer class="text-center">&copy; University 2025</footer>
</body>
</html>
<?php $conn->close(); ?>
