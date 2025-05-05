<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unauthorized</title>
    <style>
        body {
            background-color: #ffe6e6;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .message-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.2);
            animation: shake 0.4s ease;
        }
        h2 {
            color: #cc0000;
            margin-bottom: 15px;
        }
        a {
            color: #0044cc;
            text-decoration: none;
            font-weight: bold;
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Access Denied!</h2>
        <p>
            <?php
            echo isset($_SESSION['username']) 
                ? "User <strong>{$_SESSION['username']}</strong> is not authorized to access this page." 
                : "You are not authorized to view this page.";
            ?>
        </p>
        <p><a href="login.php">Return to Login</a></p>
    </div>
</body>
</html>