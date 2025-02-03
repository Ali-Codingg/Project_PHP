<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: products.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// Process the status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) { 
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $timestamp_column = '';

    if ($new_status == 'Ready') {
        $timestamp_column = 'ready_at';
    } elseif ($new_status == 'Out for Delivery') {
        $timestamp_column = 'delivery_taken_at';
    } elseif ($new_status == 'Received') {
        $timestamp_column = 'received_at';
    }

    $update_sql = "UPDATE orders SET status='$new_status'";

    if (!empty($timestamp_column)) {
        $update_sql .= ", $timestamp_column = NOW()"; 
    }

    $update_sql .= " WHERE id='$order_id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: manage_orders.php?success=1");
        exit();
    } else {
        die("Error updating order: " . mysqli_error($conn));
    }
}

// Get order details
$sql = "SELECT * FROM orders WHERE id='$order_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status - Honey E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .btn-update {
            background-color: #f7c518;
            color: white;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
  <div class="container">
    <div class="card p-4 shadow-sm">
      <h2 class="text-center mb-4">Update Order Status</h2>
      <form method="post">
        <div class="mb-3">
            <label for="status" class="form-label">Order Status:</label>
            <select name="status" class="form-select" required>
                <option value="Pending" <?= ($order['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Ready" <?= ($order['status'] == 'Ready') ? 'selected' : '' ?>>Ready</option>
                <option value="Out for Delivery" <?= ($order['status'] == 'Out for Delivery') ? 'selected' : '' ?>>Out for Delivery</option>
                <option value="Received" <?= ($order['status'] == 'Received') ? 'selected' : '' ?>>Received</option>
            </select>
        </div>

        <button type="submit" name="update_status" class="btn btn-update w-100 mb-3">Update Status</button>
      </form>

      <a href="manage_orders.php" class="btn btn-back w-100">Back to Orders</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
