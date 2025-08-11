<?php
$host = 'localhost:8889';
$user = 'root';
$password = 'root';
$dbname = 'news_db';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all news ordered by date desc
$sql = "SELECT * FROM newsfeed ORDER BY news_date DESC";
$result = $conn->query($sql);
$total_news = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>News Feed - Paragon University</title>
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
      <li class="active"><a href="newsfeed.php">News Feed</a></li>
      <li><a href="students.php">Students</a></li>
    </ul>
  </div>
</nav>

<div class="container">

    <div class="well">You are in : News Feed</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <span>Latest News</span>
            <div>
                <span class="badge"><?= $total_news ?></span>
                <a href="addnews.php" class="btn btn-success btn-sm">Add News</a>
            </div>
        </div>
        <div class="panel-body">
            <?php if ($total_news > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Date</th>
                            <th>News Content</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['news_date']) ?></td>
                                <td class="news-content"><?= nl2br(htmlspecialchars($row['news_content'])) ?></td>
                                <td>
                                    <a href="editnews.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="deletenews.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this news?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No news found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; Paragon University <?= date('Y') ?></footer>
</div>

</body>
</html>

<?php $conn->close(); ?>
