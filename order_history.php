<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC";
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
  <title>Order History - Honey E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Your Order History</h2>
    <?php if (empty($orders)): ?>
      <p>You haven't placed any orders yet. <a href="products.php">Shop now!</a></p>
    <?php else: ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
            <td>$<?php echo number_format($order['total'], 2); ?></td>
            <td><?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?></td>
            <td>
              <a href="view_order.php?order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">View</a>
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