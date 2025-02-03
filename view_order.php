<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch order details
if ($user_role === 'admin') {
    // Admin can see all orders
    $sql = "SELECT * FROM orders WHERE id='$order_id'";
} else {
    // Customers can only see their own orders
    $sql = "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'";
}

$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

// If no order is found, redirect (prevents unauthorized access)
if (!$order) {
    header("Location: order_history.php");
    exit();
}

// If admin views the order, update admin_viewed_at timestamp
if ($user_role === 'admin' && is_null($order['admin_viewed_at'])) {
    $update_sql = "UPDATE orders SET admin_viewed_at=NOW() WHERE id='$order_id'";
    mysqli_query($conn, $update_sql);
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Honey E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Arial', sans-serif; }
        .container { margin-top: 50px; }
        .card { border-radius: 10px; border: 1px solid #ddd; padding: 20px; }
        .status-box { padding: 10px; border-radius: 5px; font-weight: bold; color: white; text-align: center; }
        .status-pending { background-color: gray; }
        .status-viewed { background-color: blue; }
        .status-ready { background-color: green; }
        .status-delivery { background-color: orange; }
        .status-received { background-color: black; }
    </style>
</head>
<body>
  <div class="container">
    <div class="card shadow-sm">
      <h2 class="text-center mb-4">Order Details</h2>

      <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
      <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
      <p><strong>Placed On:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>

      <!-- Order Status Display -->
      <h4 class="mt-4">Order Status:</h4>
      <?php if (is_null($order['admin_viewed_at'])): ?>
        <div class="status-box status-pending">Admin has not seen this order yet</div>
      <?php elseif (!is_null($order['admin_viewed_at']) && is_null($order['ready_at'])): ?>
        <div class="status-box status-viewed">Admin has viewed the order</div>
      <?php elseif (!is_null($order['ready_at']) && is_null($order['delivery_taken_at'])): ?>
        <div class="status-box status-ready">Order is Ready for Pickup</div>
      <?php elseif (!is_null($order['delivery_taken_at']) && is_null($order['received_at'])): ?>
        <div class="status-box status-delivery">Order is Out for Delivery</div>
      <?php elseif (!is_null($order['received_at'])): ?>
        <div class="status-box status-received">Order has been Received</div>
      <?php endif; ?>

      <?php if ($user_role === 'admin'): ?>
        <a href="manage_orders.php" class="btn btn-primary mt-4">Back to Orders</a>
      <?php else: ?>
        <a href="order_history.php" class="btn btn-warning mt-4">Back to Order History</a>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
