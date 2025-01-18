<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for order ID in the URL
if (!isset($_GET['order_id'])) {
    header("Location: products.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch the order header information (assumes orders table contains shipping_address, order_date, total_amount, status, etc.)
$sqlOrder = "SELECT * FROM orders WHERE id='$order_id'";
$resultOrder = mysqli_query($conn, $sqlOrder);
$order = mysqli_fetch_assoc($resultOrder);

// Fetch the order items (join with products to get product name, image, etc.)
$sqlItems = "SELECT oi.*, p.name, p.description, p.image FROM order_items oi
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
  <title>Order Confirmation - Honey E-Commerce</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .order-summary { margin-top: 30px; }
    .order-header { margin-bottom: 20px; }
    .btn-home { margin-top: 20px; }
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
                      <a class="nav-link" href="order_history.php">Your Orders</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>

  <div class="container order-summary">
      <div class="row">
          <div class="col-12">
              <div class="alert alert-success text-center">
                  <h2>Thank you for your order!</h2>
                  <p>Your order has been successfully placed.</p>
              </div>
          </div>
      </div>
      
      <div class="row order-header">
          <div class="col-md-6">
              <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?></p>
          </div>
          <div class="col-md-6">
              <h4>Shipping Address:</h4>
              <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
          </div>
      </div>
      
      <h4>Order Items:</h4>
      <table class="table table-bordered">
          <thead class="table-light">
              <tr>
                  <th>Product</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($order_items as $item): ?>
              <tr>
                  <td><?php echo htmlspecialchars($item['name']); ?></td>
                  <td><?php echo htmlspecialchars($item['description']); ?></td>
                  <td>$<?php echo number_format($item['price'], 2); ?></td>
                  <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                  <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                  <td colspan="4" class="text-end"><strong>Total:</strong></td>
                  <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
              </tr>
          </tbody>
      </table>
      
      <div class="text-center">
          <a href="products.php" class="btn btn-primary btn-home">Continue Shopping</a>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
