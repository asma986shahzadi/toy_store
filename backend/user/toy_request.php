<?php
// Start the session to ensure the user is logged in
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Database connection (replace with your actual database credentials)
$host = 'localhost'; // Change to your host
$db = 'toy_store';   // Change to your database name
$user = 'root';      // Change to your database user
$pass = '';          // Change to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the toy request details from the form
    $toy_name = htmlspecialchars($_POST['toy_name']);
    $toy_description = htmlspecialchars($_POST['toy_description']);
    $user_id = $_SESSION['user_id']; // Assuming you store user id in session

    // Insert the request into the database
    $stmt = $pdo->prepare("INSERT INTO toy_requests (user_id, toy_name, toy_description) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $toy_name, $toy_description])) {
        // Redirect to a success page or display a success message
        header('Location: toy_request_success.php');
        exit();
    } else {
        $error_message = "There was an error submitting your request. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Toy</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f8fc;
      color: #333;
    }

    header {
      background: linear-gradient(to right, #007bff, #0056b3);
      color: white;
      padding: 25px 0;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    nav {
      background: white;
      display: flex;
      justify-content: center;
      gap: 40px;
      padding: 15px 0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav a {
      text-decoration: none;
      color: #007bff;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #0056b3;
    }

    .container {
      max-width: 500px;
      margin: 60px auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
      color: #222;
    }

    form input,
    form textarea {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    form input:focus,
    form textarea:focus {
      border-color: #007bff;
      outline: none;
    }

    form textarea {
      resize: vertical;
      min-height: 100px;
    }

    form button {
      width: 100%;
      background: #007bff;
      color: white;
      font-size: 16px;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    form button:hover {
      background: #0056b3;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 20px;
    }

    @media (max-width: 600px) {
      nav {
        flex-direction: column;
        gap: 15px;
        align-items: center;
      }

      .container {
        margin: 30px 20px;
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Request Toy</h1>
  </header>

  <nav>
    <a href="user_home.php">Home</a>
    <a href="cart.php">My Cart</a>
    <a href="my_orders.php">My Orders</a>
    <a href="toy_request.php">Request Toy</a>
    <a href="../common/logout.php">Logout</a>
  </nav>

  <div class="container">
    <h2>Request a Toy</h2>
    <?php if (isset($error_message)) { ?>
      <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>
    <form action="toy_request.php" method="POST">
      <input type="text" name="toy_name" placeholder="Toy Name" required>
      <textarea name="toy_description" placeholder="Describe the Toy" required></textarea>
      <button type="submit">Submit Request</button>
    </form>
  </div>

</body>
</html>