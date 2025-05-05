<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "toy_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Update order status if action button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $orderId = $_POST['order_id'];
  $action = $_POST['action'];

  if ($action == 'accept') {
    $status = 'Accepted';
  } elseif ($action == 'deliver') {
    $status = 'Delivered';
  }

  // Update the order status in the database
  $sql = "UPDATE orders SET status = '$status' WHERE order_id = $orderId";
  if ($conn->query($sql) === TRUE) {
    echo "Order status updated successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Fetch orders from the database
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Orders</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      background: #0044cc;
      color: white;
      padding: 20px;
    }
    .sidebar h2 {
      margin-bottom: 30px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      margin: 15px 0;
      padding: 10px;
      border-radius: 5px;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .main {
      margin-left: 270px;
      padding: 30px;
    }
    h1 {
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-top: 20px;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th {
      background: #0044cc;
      color: white;
    }
    .status {
      font-weight: bold;
    }
    .status-pending { color: orange; }
    .status-accepted { color: green; }
    .status-delivered { color: blue; }
    .action-btn {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
      margin-right: 5px;
    }
    .accept-btn {
      background-color: #28a745;
      color: white;
    }
    .deliver-btn {
      background-color: #007bff;
      color: white;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_toys.php"><i class="fas fa-cubes"></i> Manage Toys</a>
    <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="../common/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <h1>Orders</h1>
    <table id="ordersTable">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User</th>
          <th>Toy</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $statusClass = '';
            $acceptBtnDisabled = '';
            $deliverBtnDisabled = 'disabled';
            if ($row['status'] == 'Pending') {
              $statusClass = 'status-pending';
              $acceptBtnDisabled = '';
              $deliverBtnDisabled = 'disabled';
            } elseif ($row['status'] == 'Accepted') {
              $statusClass = 'status-accepted';
              $acceptBtnDisabled = 'disabled';
              $deliverBtnDisabled = '';
            } elseif ($row['status'] == 'Delivered') {
              $statusClass = 'status-delivered';
              $acceptBtnDisabled = 'disabled';
              $deliverBtnDisabled = 'disabled';
            }
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['user_name']}</td>
                    <td>{$row['toy_name']}</td>
                    <td class='status $statusClass'>{$row['status']}</td>
                    <td>
                      <form method='POST' action=''>
                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                        <button type='submit' name='action' value='accept' class='action-btn accept-btn' $acceptBtnDisabled>Accept</button>
                        <button type='submit' name='action' value='deliver' class='action-btn deliver-btn' $deliverBtnDisabled>Deliver</button>
                      </form>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No orders found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>