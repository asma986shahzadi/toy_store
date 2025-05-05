<?php
  // Start the session
  session_start();

  // Simulate toy data (in a real scenario, this data would be fetched from a database)
  $toys = [
    'soft_toys' => [
      'teddy_bears' => [
        [
          'name' => 'Teddy Bear',
          'price' => 999,
          'image' => '../assets/images/soft toys/teddybear/41ZRT2a7kHL._AC_UF1000,1000_QL80_.jpg',
        ],
        [
          'name' => 'Plush Teddy Bear',
          'price' => 1050,
          'image' => '../assets/images/soft toys/teddybear/b21f2ff8a4de46189fd75bee9b7307d5.jpg_720x720q80.jpg',
        ]
      ],
      'dolls' => [
        [
          'name' => 'Pretty Doll',
          'price' => 700,
          'image' => '../assets/images/soft toys/doll images/1bcc5d23-fae0-47b9-947c-eeadd3184fc9_800x800.jpeg.a.jpeg',
        ],
        [
          'name' => 'Cute Doll',
          'price' => 780,
          'image' => '../assets/images/soft toys/doll images/538658948c84ee22ad9dd90c8cd39b52.jpg',
        ]
      ]
    ],
  ];

  // Check if user is logged in
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }

  // Add to cart logic (simplified)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $toy_name = $_POST['toy_name'];
    $toy_price = $_POST['toy_price'];
    $toy_image = $_POST['toy_image'];

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
      'name' => $toy_name,
      'price' => $toy_price,
      'image' => $toy_image
    ];

    echo "<script>alert('$toy_name added to cart!');</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Home - Toy Finder</title>
  <style>
    /* Style definitions here */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/images/bg1.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    header {
      background: rgba(0, 123, 255, 0.9);
      color: white;
      padding: 20px 20px 20px 100px;
      display: flex;
      align-items: center;
      position: relative;
    }

    header img.logo {
      position: absolute;
      left: 20px;
      width: 60px;
      height: 60px;
    }

    header h1 {
      margin: 0 auto;
      font-size: 28px;
    }

    nav {
      background: white;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      padding: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    nav a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      transition: 0.3s;
    }

    nav a:hover {
      color: #0056b3;
    }

    .search-bar {
      text-align: center;
      margin: 20px;
    }

    .search-bar form {
      display: inline-flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .search-bar input {
      padding: 10px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .search-bar button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .search-bar button:hover {
      background-color: #0056b3;
    }

    .container {
      padding: 40px;
      background-color: rgba(255, 255, 255, 0.5);
      margin: 30px auto;
      max-width: 1200px;
      border-radius: 10px;
    }

    .category {
      margin-bottom: 50px;
    }

    .sub-category {
      margin-bottom: 30px;
    }

    .container h2,
    .container h3 {
      color: #333;
      margin-bottom: 15px;
    }

    .toy-gallery {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .toy {
      background: rgba(255, 255, 255, 0.7);
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      width: 200px;
      text-align: center;
      transition: transform 0.3s ease;
      animation: fadeIn 0.4s ease-in;
    }

    .toy:hover {
      transform: translateY(-5px);
    }

    .toy img {
      max-width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
    }

    .toy h4 {
      margin: 10px 0 5px;
      color: #333;
    }

    .toy p {
      color: #555;
      font-weight: bold;
    }

    .toy button {
      margin-top: 10px;
      padding: 8px 15px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .toy button:hover {
      background-color: #218838;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    @media screen and (max-width: 768px) {
      header h1 {
        font-size: 20px;
      }

      .search-bar form {
        flex-direction: column;
        align-items: center;
      }

      .search-bar input, .search-bar button {
        width: 100%;
      }

      .toy {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <header>
    <img src="../assets/images/logo.png" alt="Toy Finder Logo" class="logo" />
    <h1>Welcome to Toy Finder</h1>
  </header>

  <nav>
    <a href="user_home.php">Home</a>
    <a href="cart.php">My Cart</a>
    <a href="my_orders.php">My Orders</a>
    <a href="toy_request.php">Request Toy</a>
    <a href="../common/login.php">Logout</a>
  </nav>

  <div class="search-bar">
    <form action="search_results.php" method="GET">
      <input type="text" name="query" placeholder="Search toys..." required />
      <button type="submit">Search</button>
    </form>
  </div>

  <div class="container">

    <!-- Soft Toys -->
    <div class="category">
      <h2>Soft Toys</h2>

      <!-- Teddy Bears -->
      <div class="sub-category">
        <h3>Teddy Bears</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['teddy_bears'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Dolls -->
      <div class="sub-category">
        <h3>Dolls</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['dolls'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- KeyChains -->
      <div class="sub-category">
        <h3>KeyChains</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['key chain'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- Technical Toys -->
    <div class="category">
      <h2>Soft Toys</h2>

      <!-- Bikes -->
      <div class="sub-category">
        <h3>Bikes</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['bikes'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Robots -->
      <div class="sub-category">
        <h3>Robots</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['robot'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tabs -->
      <div class="sub-category">
        <h3>Tabs</h3>
        <div class="toy-gallery">
          <?php foreach ($toys['soft_toys']['tabs'] as $toy): ?>
          <div class="toy">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>" />
            <h4><?php echo $toy['name']; ?></h4>
            <p>Rs. <?php echo $toy['price']; ?></p>
            <form action="user_home.php" method="POST">
              <input type="hidden" name="toy_name" value="<?php echo $toy['name']; ?>" />
              <input type="hidden" name="toy_price" value="<?php echo $toy['price']; ?>" />
              <input type="hidden" name="toy_image" value="<?php echo $toy['image']; ?>" />
              <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Add more categories here -->

    </div>

  </div>

</body>
</html>