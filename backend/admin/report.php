<?php
// Assuming you are connected to a database
include('config.php');

// Fetch total sales for the month
$salesQuery = "SELECT SUM(amount) AS total_sales FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$salesResult = mysqli_query($conn, $salesQuery);
$salesData = mysqli_fetch_assoc($salesResult);
$totalSales = $salesData['total_sales'];

// Fetch the top-selling toy
$topToyQuery = "SELECT toy_name, COUNT(*) AS sold_count FROM orders GROUP BY toy_name ORDER BY sold_count DESC LIMIT 1";
$topToyResult = mysqli_query($conn, $topToyQuery);
$topToyData = mysqli_fetch_assoc($topToyResult);
$topToy = $topToyData['toy_name'];
$topToyCount = $topToyData['sold_count'];

// Fetch the new users registered this week
$newUsersQuery = "SELECT COUNT(*) AS new_users FROM users WHERE registration_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
$newUsersResult = mysqli_query($conn, $newUsersQuery);
$newUsersData = mysqli_fetch_assoc($newUsersResult);
$newUsers = $newUsersData['new_users'];

// Fetch total orders for the month
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$totalOrdersResult = mysqli_query($conn, $totalOrdersQuery);
$totalOrdersData = mysqli_fetch_assoc($totalOrdersResult);
$totalOrders = $totalOrdersData['total_orders'];

// Calculate monthly growth (this example assumes the previous month's data is available)
$previousMonthSalesQuery = "SELECT SUM(amount) AS previous_month_sales FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH) AND order_date < DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$previousMonthSalesResult = mysqli_query($conn, $previousMonthSalesQuery);
$previousMonthSalesData = mysqli_fetch_assoc($previousMonthSalesResult);
$previousMonthSales = $previousMonthSalesData['previous_month_sales'];
$growth = ($totalSales - $previousMonthSales) / $previousMonthSales * 100;

// Fetch refund requests
$refundRequestsQuery = "SELECT COUNT(*) AS refund_requests FROM refunds WHERE status = 'pending'";
$refundRequestsResult = mysqli_query($conn, $refundRequestsQuery);
$refundRequestsData = mysqli_fetch_assoc($refundRequestsResult);
$refundRequests = $refundRequestsData['refund_requests'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Reports</title>
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
    .main h1 {
      color: #333;
      margin-bottom: 30px;
    }

    .report-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .report-box {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: 0.3s;
    }

    .report-box:hover {
      transform: translateY(-5px);
    }

    .report-icon {
      font-size: 30px;
      margin-bottom: 10px;
      color: #0044cc;
    }

    .report-box h3 {
      margin-bottom: 5px;
      color: #222;
    }

    .report-box p {
      font-size: 16px;
      color: #555;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_toys.html"><i class="fas fa-cubes"></i> Manage Toys</a>
    <a href="orders.html"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="../common/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <h1>Reports Overview</h1>
    <div class="report-container">
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-dollar-sign"></i></div>
        <h3>Total Sales</h3>
        <p>Rs. <?php echo number_format($totalSales, 2); ?> this month</p>
      </div>
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-cube"></i></div>
        <h3>Top Toy</h3>
        <p><?php echo $topToy; ?> - <?php echo $topToyCount; ?> sold</p>
      </div>
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-user-plus"></i></div>
        <h3>New Users</h3>
        <p><?php echo $newUsers; ?> new users registered this week</p>
      </div>
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-box"></i></div>
        <h3>Total Orders</h3>
        <p><?php echo $totalOrders; ?> orders placed this month</p>
      </div>
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-arrow-up"></i></div>
        <h3>Monthly Growth</h3>
        <p>+<?php echo number_format($growth, 2); ?>% compared to last month</p>
      </div>
      <div class="report-box">
        <div class="report-icon"><i class="fas fa-undo"></i></div>
        <h3>Refund Requests</h3>
        <p><?php echo $refundRequests; ?> pending refund requests</p>
      </div>
    </div>
  </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>