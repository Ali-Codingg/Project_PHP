<?php
session_start();
include('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the order ID from the query string
if (!isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$sqlOrder = "SELECT * FROM orders WHERE id='$order_id'";
$resultOrder = mysqli_query($conn, $sqlOrder);
$order = mysqli_fetch_assoc($resultOrder);

// Fetch order items
$sqlItems = "SELECT oi.*, p.name, p.description, p.image FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id='$order_id'";
$resultItems = mysqli_query($conn, $sqlItems);
$order_items = [];
while($row = mysqli_fetch_assoc($resultItems)) {
    $order_items[] = $row;
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Order - Honey E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body { background-color: #f8f9fa; }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
          <a class="navbar-brand" href="products.php">Honey E-Commerce</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" href="order_history.php">Order History</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
  
  <!-- Main Container -->
  <div class="container mt-5">
      <h2>Order Details - Order #<?php echo htmlspecialchars($order_id); ?></h2>
      <div class="card mb-3">
          <div class="card-body">
              <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
              <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
              <p><strong>Total:</strong> $<?php echo number_format($order['total'],2); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?></p>
          </div>
      </div>
      
      <h4>Items:</h4>
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($order_items as $item): ?>
              <tr>
                  <td><?php echo htmlspecialchars($item['name']); ?></td>
                  <td>$<?php echo number_format($item['price'],2); ?></td>
                  <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                  <td>$<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
              </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
      <a href="order_history.php" class="btn btn-secondary">Back to Order History</a>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
