<?php
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Dummy credentials (replace with DB check in real application)
    $credentials = [
        "admin" => ["username" => "admin", "password" => "admin123"],
        "user" => ["username" => "user", "password" => "user123"]
    ];

    if (isset($credentials[$role]) &&
        $credentials[$role]["username"] === $username &&
        $credentials[$role]["password"] === $password) {

        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;

        if ($role === "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/user_home.php");
        }
        exit();
    } else {
        $error = "Invalid credentials. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 35px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 370px;
            animation: fadeIn 0.6s ease-in-out;
        }
        .login-box h2 {
            margin-bottom: 25px;
            color: #0044cc;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        button {
            background: #0044cc;
            color: white;
            font-weight: bold;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        button:hover {
            background: #0033aa;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: left;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.9);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <select name="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>