<?php
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$name || !$email || !$password) {
        $error = "Please fill in all fields.";
    } else {
        // Simulate storing in a file (you can replace this with DB)
        $file = fopen("users.txt", "a");
        fwrite($file, "$name|$email|$password\n");
        fclose($file);

        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - ToyShop</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background:linear-gradient(to right, #8360c3, #2ebf91); display:flex; justify-content:center; align-items:center; height:100vh;">
    <div style="background:white; padding:40px; border-radius:10px; box-shadow:0 8px 16px rgba(0,0,0,0.2); width:400px; animation:fadeIn 1s ease-in;">
        <h2 style="text-align:center; margin-bottom:20px; color:#333;">Register</h2>
        <?php if ($error): ?>
            <p style="color:red; font-size:14px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <label>Full Name</label><br>
            <input type="text" name="fullname" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;"><br>
            <label>Email</label><br>
            <input type="email" name="email" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;"><br>
            <label>Password</label><br>
            <input type="password" name="password" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;"><br>
            <button type="submit" style="width:100%; padding:10px; background:#2ebf91; color:white; border:none; border-radius:5px; cursor:pointer; font-size:16px; transition:0.3s;">Register</button>
        </form>
        <p style="text-align:center; margin-top:15px;">Already have an account? <a href="login.php" style="color:#2ebf91;">Login</a></p>
    </div>

    <style>
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</body>
</html>