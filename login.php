<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hardcoded login
    if ($email === 'user@gmail.com' && $password === 'kak') {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb); /* soft light blue gradient */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.5;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-direction: alternate;
            mix-blend-mode: screen;
            z-index: 0;
        }

        /* Light blue blobs */
        .blob1 {
            width: 320px;
            height: 320px;
            background: #83aaff; /* medium light blue */
            top: 10%;
            left: 5%;
            animation-name: float1;
            animation-duration: 8s;
        }

        .blob2 {
            width: 400px;
            height: 400px;
            background: #b3d1ff; /* pale light blue */
            bottom: 15%;
            right: 10%;
            animation-name: float2;
            animation-duration: 10s;
        }

        .blob3 {
            width: 250px;
            height: 250px;
            background: #4a90e2; /* richer blue */
            top: 40%;
            right: 25%;
            animation-name: float3;
            animation-duration: 12s;
            opacity: 0.4;
        }

        .blob4 {
            width: 280px;
            height: 280px;
            background: #9ecfff; /* soft baby blue */
            bottom: 30%;
            left: 20%;
            animation-name: float4;
            animation-duration: 9s;
            opacity: 0.45;
        }

        @keyframes float1 {
            0%   { transform: translate(0, 0); }
            50%  { transform: translate(20px, -30px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes float2 {
            0%   { transform: translate(0, 0); }
            50%  { transform: translate(-25px, 35px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes float3 {
            0%   { transform: translate(0, 0); }
            50%  { transform: translate(15px, 20px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes float4 {
            0%   { transform: translate(0, 0); }
            50%  { transform: translate(-20px, -25px); }
            100% { transform: translate(0, 0); }
        }

        form {
            position: relative;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 40px 50px;
            width: 360px;
            z-index: 10;
            animation: fadeInUp 1s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2.8rem;
            letter-spacing: 2px;
            text-align: center;
            text-shadow: 0 2px 6px rgba(0,0,0,0.15);
            color: #e0f0ff;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            color: #cde8ff;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 18px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: none;
            background: rgba(255,255,255,0.25);
            color: #003366;
            font-size: 1.1rem;
            outline: none;
            box-shadow: inset 0 0 5px rgba(255,255,255,0.5);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            font-weight: 600;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            background: rgba(255,255,255,0.45);
            box-shadow: 0 0 12px 2px #83aaff;
            color: #001933;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px 0;
            background: linear-gradient(135deg, #83aaff, #4a90e2);
            border: none;
            border-radius: 12px;
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            letter-spacing: 1.5px;
            box-shadow: 0 8px 15px rgba(131, 170, 255, 0.5);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #4a90e2, #83aaff);
            box-shadow: 0 12px 25px rgba(74, 144, 226, 0.7);
        }

        p.error {
            margin-top: 20px;
            font-weight: 600;
            color: #ff4e42;
            letter-spacing: 1px;
            text-shadow: 0 0 5px #ff4e42;
            text-align: center;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            z-index: 10;
        }

        @media (max-width: 400px) {
            form {
                width: 90vw;
                padding: 30px 20px;
            }
            h1 {
                font-size: 2rem;
            }
            input[type="submit"] {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Light blue floating blobs -->
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="blob blob3"></div>
    <div class="blob blob4"></div>

    <!-- Paragon International University Logo -->
    <img src="https://paragoniu.edu.kh/wp-content/uploads/2022/01/paragon-logo-2@2x.png" alt="Paragon International University Logo" class="logo">

    <form method="post" action="login.php" autocomplete="off" spellcheck="false">
        <h1>Sign In</h1>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autofocus>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>

        <input type="submit" value="Login">

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>

</body>
</html>
