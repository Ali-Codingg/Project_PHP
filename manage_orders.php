<?php
session_start();
include('config.php');

// Ensure the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: products.php");
    exit();
}

$sql = "SELECT o.*, u.name AS customer_name FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);

$orders = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders - Honey E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2>Manage Orders</h2>
    <?php if (empty($orders)): ?>
      <div class="alert alert-info">No orders found.</div>
    <?php else: ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
            <td>$<?php echo number_format($order['total_amount'],2); ?></td>
            <td><?php echo htmlspecialchars($order['status'] ); ?></td>
            <td>
              <a href="view_order.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">View</a>
              <a href="update_order_status.php?order_id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm">Update Status</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
