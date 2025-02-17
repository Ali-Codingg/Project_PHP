<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if order_id is provided in the URL
if (!isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details from the orders table (adjust column names as needed)
$sqlOrder = "SELECT * FROM orders WHERE id='$order_id' AND user_id='{$_SESSION['user_id']}'";
$resultOrder = mysqli_query($conn, $sqlOrder);
$order = mysqli_fetch_assoc($resultOrder);

if (!$order) {
    header("Location: order_history.php");
    exit();
}

// Fetch order items from the order_items table (joined with products for additional details)
$sqlItems = "SELECT oi.*, p.name, p.price FROM order_items oi 
             LEFT JOIN products p ON oi.product_id = p.id 
             WHERE oi.order_id='$order_id'";
$resultItems = mysqli_query($conn, $sqlItems);
$order_items = [];
while ($row = mysqli_fetch_assoc($resultItems)) {
    $order_items[] = $row;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed - Honey E-Commerce</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { 
      background-color: #f8f9fa; 
    }
    .order-header {
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
          <a class="navbar-brand" href="index.php">Honey E-Commerce</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#navbarNav" aria-controls="navbarNav" 
                  aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" href="order_history.php">Order History</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="products.php">Products</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
  
  <!-- Main Container -->
  <div class="container order-header">
      <div class="alert alert-success text-center">
          <h2>Thank You for Your Order!</h2>
          <p>Your order has been confirmed successfully.</p>
      </div>
  </div>
  
  <div class="container">
      <!-- Order Summary Card -->
      <div class="card mb-4">
          <div class="card-body">
              <h4>Order Details</h4>
              <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
              <p><strong>Shipping Address:</strong><br>
                <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
              <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?></p>
          </div>
      </div>
      
      <!-- Order Items Table -->
      <h4>Items in Your Order</h4>
      <table class="table table-striped">
          <thead class="table-light">
              <tr>
                  <th>Product Name</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
              </tr>
          </thead>
          <tbody>
              <?php 
              $calculatedTotal = 0;
              foreach($order_items as $item): 
                  $subtotal = $item['price'] * $item['quantity'];
                  $calculatedTotal += $subtotal;
              ?>
              <tr>
                  <td><?php echo htmlspecialchars($item['name']); ?></td>
                  <td>$<?php echo number_format($item['price'], 2); ?></td>
                  <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                  <td>$<?php echo number_format($subtotal, 2); ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                  <td colspan="3" class="text-end"><strong>Total:</strong></td>
                  <td><strong>$<?php echo number_format($calculatedTotal, 2); ?></strong></td>
              </tr>
          </tbody>
      </table>
      
      <div class="text-center">
          <a href="products.php" class="btn btn-warning">Continue Shopping</a>
      </div>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
