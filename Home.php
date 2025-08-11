<?php
// ====== DATABASE LOGIC ====== //
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

// Connect to news_db
$conn3 = new mysqli($host, $user, $password, 'news_db');
if ($conn3->connect_error) {
    die("Connection failed (news_db): " . $conn3->connect_error);
}

// Fetch top students from school_db.tblstudentsdb
$sql1 = "SELECT id, Firstname, Lastname, gender, class, student_rank 
         FROM tblstudentsdb ORDER BY student_rank ASC LIMIT 10";
$result1 = $conn1->query($sql1);

// Fetch top students from student_db.tblstudents
$sql2 = "SELECT id, name, gender, class, student_rank 
         FROM tblstudents ORDER BY student_rank ASC LIMIT 10";
$result2 = $conn2->query($sql2);

// Total counts
$total_school = $conn1->query("SELECT COUNT(*) as cnt FROM tblstudentsdb")->fetch_assoc()['cnt'];
$total_student = $conn2->query("SELECT COUNT(*) as cnt FROM tblstudents")->fetch_assoc()['cnt'];
$total_students = $total_school + $total_student;

// Combine arrays
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
    $fullname = $row['name'];
    $firstname = $fullname;
    $lastname = '';
    if (strpos($fullname, ' ') !== false) {
        list($firstname, $lastname) = explode(' ', $fullname, 2);
    }
    $students[] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'gender' => $row['gender'],
        'class' => $row['class'],
        'rank' => $row['student_rank']
    ];
}
usort($students, fn($a, $b) => $a['rank'] <=> $b['rank']);
$top_students = array_slice($students, 0, 4);

// Fetch latest news - limit 5 latest
$sqlNews = "SELECT * FROM newsfeed ORDER BY news_date DESC LIMIT 5";
$resultNews = $conn3->query($sqlNews);
$total_news = $resultNews->num_rows;

$conn1->close();
$conn2->close();
$conn3->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Paragon University Welcome</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <style>
    /* Your original styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body, html {
      height: 100%;
      width: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Background */
    .background {
      background-image: url('paragonII.jpg');
      background-size: cover;
      background-position: center;
      height: 50vh;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: white;
      padding: 4px 5px;
    }
    /* Navbar Top */
    .navbarTop {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgb(35, 28, 131);
      padding: 20px 5px;
      font-family: 'Merriweather', serif;
      letter-spacing: 2px;
      text-transform: uppercase;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
    .navbarTop ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin-left: 1200px;
    }
    .navbarTop ul li a {
      color: #eae7e7 !important;
      text-decoration: none;
      font-weight: bold;
    }
    /* Main Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgb(255, 253, 253);
      padding: 10px 5px;
      font-family: 'Merriweather', serif;
      letter-spacing: 2px;
      text-transform: uppercase;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
    .navbar img {
      height: 60px;
      margin-left: 70px;
    }
    .navbar ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin-right: 10px;
    }
    .navbar ul li a {
      color: #000 !important;
      text-decoration: none;
      font-weight: bold;
    }
    .navbar ul li a:hover {
      text-decoration: underline;
    }
    /* Welcome & Apply Now */
    .center-text {
      text-align: center;
      margin-top: 100px;
    }
    .message {
      font-size: 2rem;
      font-weight: bold;
      background: rgba(9, 51, 90, 0.6);
      width: 500px;
      border-radius: 20px;
      display: inline-block;
      opacity: 0;
      transition: opacity 1s ease-in-out;
      margin-left: auto;
      margin-right: auto;
    }
    .show { opacity: 1; }
    .sub-message {
      margin-top: 10px;
      font-size: 1rem;
      animation: scroll-text 10s linear infinite;
      white-space: nowrap;
      overflow: hidden;
    }
    @keyframes scroll-text {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }
    /* Promo */
    .promo {
      text-align: center;
      font-size: 1.2rem;
      padding: 20px;
      background: rgba(0, 0, 0, 0.4);
    }
    /* Student Cards */
    .student-card {
      background: white;
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      margin-bottom: 20px;
      position: relative;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease-in-out;
    }
    .student-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
  
    .student-card h4 {
      font-weight: bold;
      margin-bottom: 5px;
      color: #1e2a78;
    }
    .rank-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      background: gold;
      color: black;
      font-weight: bold;
      padding: 8px 12px;
      border-radius: 50px;
      font-size: 14px;
    }
    .rank-1 {
      box-shadow: 0 0 20px gold !important;
      border: 2px solid gold;
    }

    /* NEWSFEED section - enhanced style for cool look */
    .newsfeed-container {
      margin-top: 60px;
      margin-bottom: 40px;
    }

    .newsfeed-title {
      font-weight: bold;
      font-size: 32px;
      color: #004085; /* Deep blue */
      text-align: center;
      margin-bottom: 30px;
      text-shadow: 1px 1px 3px rgba(0, 64, 133, 0.6);
      letter-spacing: 1.5px;
    }

    .news-card {
      background: linear-gradient(135deg, #e0f0ff 0%, #a9d6ff 100%);
      border-radius: 15px;
      padding: 20px 25px;
      box-shadow: 0 8px 20px rgba(0, 123, 255, 0.25);
      margin-bottom: 25px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 8px solid #007bff; /* Bright blue accent */
    }

    .news-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 123, 255, 0.5);
    }

    .news-date {
      color: #003366; /* Darker blue */
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 12px;
      font-family: 'Segoe UI Semibold', Tahoma, Geneva, Verdana, sans-serif;
      letter-spacing: 0.8px;
    }

    .news-content {
      white-space: pre-wrap;
      font-size: 1.15rem;
      line-height: 1.5;
      color: #1a1a1a;
      font-weight: 500;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Optional: add a subtle gradient underline below the title */
    .newsfeed-title::after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      margin: 8px auto 0;
      background: linear-gradient(90deg, #007bff, #00c6ff);
      border-radius: 3px;
    }
  </style>
</head>
<body>

<!-- NAVIGATION -->
<section>
 <div class="navbarTop">
   <ul>
      <li><a href="#"><h6>Home</h6></a></li>
      <li><a href="login.php"><h6>Login</h6></a></li>
      <li><a href="#"><h6>Learn More</h6></a></li>
    </ul>
 </div>
</section>
<section>
  <div class="navbar">
    <img src="https://paragoniu.edu.kh/wp-content/uploads/2022/01/paragon-logo-2@2x.png" alt="">
    <ul>
       <li><a href=""><h6>About</h6></a></li>
       <li><a href=""><h6>International</h6></a></li>
       <li><a href=""><h6>Research</h6></a></li>
       <li><a href=""><h6>Campus</h6></a></li>
       <li><a href=""><h6>Business</h6></a></li>
       <li><a href=""><h6>Partnership</h6></a></li>
       <li><a href=""><h6>Paragon Student</h6></a></li>
    </ul>
  </div>
</section>

<!-- WELCOME -->
<section>
<div class="background">
    <div id="welcomeMessage" class="message show center-text"><h3>WELCOME TO PARAGON UNIVERSITY</h3></div>
    <div id="applyMessage" class="message center-text"><h3>APPLY NOW</h3></div>
    <div class="sub-message"><h5>✨ Enroll now at Paragon University – Your future starts here! ✨</h5></div>
</div>
<div class="promo">
  Paragon University offers world-class education with experienced faculty, modern technology, and industry partnerships. Join us to explore your full potential in Computer Science, Business, Engineering, and more!
</div>
</section>

<!-- TOP 4 STUDENTS -->
<div class="container" style="margin-top: 40px;">
  <h2 class="text-center" style="margin-bottom: 30px; font-weight: bold; color: #1e2a78;">
    🌟 Top 4 Students 🌟
  </h2>
  <p class="text-center" style="margin-bottom: 20px;">
    Total Students: <span class="badge badge-primary" style="font-size: 16px; background-color: #1e2a78;"><?= $total_students ?></span>
  </p>

  <div class="row">
    <?php foreach ($top_students as $i => $student): ?>
      <div class="col-md-3 col-sm-6">
        <div class="student-card <?= ($i == 0) ? 'rank-1' : '' ?>">
          <div class="rank-badge">#<?= $student['rank'] ?></div>
          
          <h4><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h4>
          <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></p>
          <p><strong>Class:</strong> <?= htmlspecialchars($student['class']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- NEWSFEED -->
<div class="container newsfeed-container">
  <h2 class="newsfeed-title">📰 Latest News</h2>

  <?php if ($total_news > 0): ?>
    <?php while($news = $resultNews->fetch_assoc()): ?>
      <div class="news-card">
        <div class="news-date"><?= htmlspecialchars($news['news_date']) ?></div>
        <div class="news-content"><?= nl2br(htmlspecialchars($news['news_content'])) ?></div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No news found.</p>
  <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="text-center" style="margin-top: 40px; padding: 10px; background-color: #222; color: #fff;">
  &copy; University <?= date('Y') ?>
</footer>

<script>
let isWelcome = true;
setInterval(() => {
  if (isWelcome) {
    document.getElementById("welcomeMessage").classList.remove("show");
    document.getElementById("applyMessage").classList.add("show");
  } else {
    document.getElementById("applyMessage").classList.remove("show");
    document.getElementById("welcomeMessage").classList.add("show");
  }
  isWelcome = !isWelcome;
}, 3000);
</script>

</body>
</html>
