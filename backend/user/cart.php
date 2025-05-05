<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart - Toy Finder</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    header {
      background: #007bff;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .toy-gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .toy {
      width: 220px;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      background-color: #f1f1f1;
    }

    .toy img {
      max-width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
    }

    .toy h4 {
      margin: 10px 0;
    }

    .toy p {
      font-weight: bold;
      margin: 5px 0;
    }

    .order-now-btn {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .remove-btn {
      margin-top: 5px;
      padding: 6px 14px;
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .total {
      margin-top: 30px;
      font-size: 18px;
      text-align: right;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <header>
    <h1>My Cart</h1>
  </header>

  <div class="container" id="cart-items">
    <!-- Cart items will be injected here -->
    <?php
    // Initialize cart if not yet initialized
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Handle item removal
    if (isset($_GET['remove'])) {
      $index = $_GET['remove'];
      array_splice($_SESSION['cart'], $index, 1);
    }

    // Handle order placement
    if (isset($_GET['order'])) {
      $index = $_GET['order'];
      $item = $_SESSION['cart'][$index];
      // Process order (for now, just remove from cart)
      array_splice($_SESSION['cart'], $index, 1);
      // Store orders in session (could be replaced by a database)
      if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
      }
      $item['status'] = 'Processing';
      $_SESSION['orders'][] = $item;
    }

    // Display cart items
    if (count($_SESSION['cart']) === 0) {
      echo "<h2>Your cart is empty.</h2>";
    } else {
      $total = 0;
      echo '<div class="toy-gallery">';
      foreach ($_SESSION['cart'] as $index => $item) {
        $total += $item['price'];
        echo '<div class="toy">';
        echo '<img src="' . $item['image'] . '" alt="' . $item['name'] . '">';
        echo '<h4>' . $item['name'] . '</h4>';
        echo '<p>Rs. ' . $item['price'] . '</p>';
        echo '<a href="?order=' . $index . '"><button class="order-now-btn">Order Now</button></a>';
        echo '<a href="?remove=' . $index . '"><button class="remove-btn">Remove</button></a>';
        echo '</div>';
      }
      echo '</div>';
      echo '<div class="total">Total: Rs. ' . $total . '</div>';
    }
    ?>
  </div>

</body>
</html>