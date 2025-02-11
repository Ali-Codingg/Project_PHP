<?php
session_start();
include('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Verify order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$sql = "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: order_history.php");
    exit();
}

// Confirm order receipt
if (isset($_POST['confirm_receipt'])) {
    $received_at = date('Y-m-d H:i:s');
    $update_sql = "UPDATE orders SET received_at='$received_at' WHERE id='$order_id'";
    mysqli_query($conn, $update_sql);
    header("Location: order_confirmed.php?order_id=" . $order_id);
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Order - Honey E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Confirm Order Receipt</h2>
    <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
    <p>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>
    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
    <p>Delivery Taken: <?php echo htmlspecialchars($order['delivery_taken_at'] ?? 'Not yet'); ?></p>
    
    <form method="POST">
      <button type="submit" name="confirm_receipt" class="btn btn-success">Confirm Receipt</button>
      <a href="order_history.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>