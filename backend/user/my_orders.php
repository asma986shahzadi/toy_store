<?php
session_start();

// Assuming the orders are stored in a database, replace this with actual DB connection and fetching logic
// Simulating order data as an example
$orders = [
    [
        'name' => 'Toy 1',
        'price' => 1000,
        'status' => 'Processing'
    ],
    [
        'name' => 'Toy 2',
        'price' => 1500,
        'status' => 'Shipped'
    ],
    [
        'name' => 'Toy 3',
        'price' => 800,
        'status' => 'Delivered'
    ]
];

// Assuming you're checking if the user is logged in
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Orders</title>
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
      padding: 50px 20px;
      max-width: 1000px;
      margin: auto;
    }

    .container h2 {
      font-size: 32px;
      margin-bottom: 30px;
      text-align: center;
    }

    .order-item {
      background: #ffffff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.07);
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .order-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .order-item h4 {
      margin: 0;
      font-size: 22px;
      color: #222;
    }

    .order-item p {
      margin: 5px 0;
      font-size: 16px;
      color: #555;
    }

    .order-status {
      font-weight: bold;
      font-size: 16px;
      color: #28a745;
    }

    @media (max-width: 600px) {
      .order-item {
        flex-direction: column;
        align-items: flex-start;
      }
      .order-item div:last-child {
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>My Orders</h1>
  </header>

  <nav>
    <a href="user_home.php">Home</a>
    <a href="cart.php">My Cart</a>
    <a href="my_order.php">My Orders</a>
    <a href="toy_request.php">Request Toy</a>
    <a href="logout.php">Logout</a>
  </nav>

  <div class="container">
    <h2>Your Orders</h2>
    <?php if (count($orders) === 0) { ?>
      <p style="text-align:center;">You have no orders yet.</p>
    <?php } else {
      foreach ($orders as $order) { ?>
        <div class="order-item">
          <div>
            <h4><?php echo $order['name']; ?></h4>
            <p>Rs. <?php echo $order['price']; ?></p>
          </div>
          <div>
            <p>Status: <span class="order-status"><?php echo $order['status']; ?></span></p>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  </div>

</body>
</html>
