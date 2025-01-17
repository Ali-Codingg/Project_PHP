<?php
// order_list.php
include 'config.php';
include 'header.php';

// Fetch all orders from the database
$query = "SELECT o.id, u.name AS user_name, o.status, o.total_amount, o.created_at 
          FROM orders o 
          JOIN users u ON o.user_id = u.id
          ORDER BY o.created_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0): ?>
    <h1>Order List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>$<?= number_format($row['total_amount'], 2) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="order_details.php?id=<?= $row['id'] ?>">Details</a> |
                    <a href="update_order_status.php?id=<?= $row['id'] ?>">Update Status</a> |
                    <a href="delete_order.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No orders found.</p>
<?php endif;

include 'footer.php';
