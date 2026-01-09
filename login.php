<?php
// session_start();
// include '../includes/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>HOD Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: url('../images/college.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: -2;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            backdrop-filter: blur(8px);
            background-color: rgba(50, 0, 70, 0.6);
            z-index: -1;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
            min-height: 100vh;
        }

        .login-container {
            width: 400px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            padding: 40px 32px;
            box-shadow: 0 12px 30px rgba(107, 0, 160, 0.2);
        }

        h2 {
            color: #6a0dad;
            font-weight: 700;
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 28px;
            letter-spacing: 1.2px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #6a0dad;
            font-size: 1.05rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            font-size: 1.05rem;
            border: 2px solid #b388eb;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 22px;
            color: #333;
            background: #fff;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #6a0dad;
            box-shadow: 0 0 12px rgba(106, 13, 173, 0.5);
            outline: none;
            background-color: #f9f3ff;
        }

        button.btn-primary {
            background-color: #6a0dad;
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 14px 0;
            border: none;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            margin-top: 5px;
            transition: background-color 0.3s ease;
            letter-spacing: 0.8px;
        }

        button.btn-primary:hover {
            background-color: #4b0082;
        }

        .btn-secondary {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #999;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Background image and overlay -->
<div class="background"></div>
<div class="overlay"></div>

<div class="login-container">
    <h2>HOD Login</h2>
    <form action="hod_login_process.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <button type="submit" class="btn-primary">Login</button>
        <a href="../index.html" class="btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
