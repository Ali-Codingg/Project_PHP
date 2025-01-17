<?php

include 'config.php';
include 'header.php';

// Check if the order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<p>Order ID is missing.</p>';
    include 'footer.php';
    exit;
}

$order_id = (int)$_GET['id'];


$query = "SELECT o.id, u.name AS user_name, o.status, o.total_amount, o.created_at 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<p>Order not found.</p>';
    include 'footer.php';
    exit;
}

$order = $result->fetch_assoc();


$query_items = "SELECT oi.product_id, p.name AS product_name, oi.quantity, oi.price 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($query_items);
$stmt_items->bind_param('i', $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
?>

<h1>Order Details</h1>
<p><strong>Order ID:</strong> <?= $order['id'] ?></p>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
<p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
<p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
<p><strong>Date:</strong> <?= $order['created_at'] ?></p>

<h2>Order Items</h2>
<?php if ($result_items->num_rows > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($item = $result_items->fetch_assoc()): ?>
            <tr>
                <td><?= $item['product_id'] ?></td>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No items found for this order.</p>
<?php endif; ?>

<a href="order_list.php">Back to Orders</a>

<?php
include 'footer.php';
