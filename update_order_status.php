<?php
session_start();
include('config.php');

// Ensure the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: products.php");
    exit();
}

// Verify order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// Process the form submission to update the order status
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $update_sql = "UPDATE orders SET status='$new_status' WHERE id='$order_id'";
    mysqli_query($conn, $update_sql);
    header("Location: manage_orders.php");
    exit();
}

// Retrieve the current order details
$sql = "SELECT * FROM orders WHERE id='$order_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Order Status - Honey E-Commerce</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body { background-color: #f8f9fa; }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2>Update Order Status</h2>
    <form action="update_order_status.php?order_id=<?php echo htmlspecialchars($order_id); ?>" method="POST">
      <div class="mb-3">
        <label for="status" class="form-label">New Status:</label>
        <select name="status" id="status" class="form-select">
          <option value="Pending" <?php if (($order['status'] ?? '') == 'Pending') echo 'selected'; ?>>Pending</option>
          <option value="Completed" <?php if (($order['status'] ?? '') == 'Completed') echo 'selected'; ?>>Completed</option>
          <option value="Cancelled" <?php if (($order['status'] ?? '') == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
      </div>
      <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
      <a href="manage_orders.php" class="btn btn-secondary">Back to Manage Orders</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
