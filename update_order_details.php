<?php
// update_order_status.php
include 'config.php';
include 'header.php';

// CSRF Token generation for enhanced security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if the order ID is provided
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo '<p>Invalid order ID.</p>';
    if (file_exists('footer.php')) {
        include 'footer.php';
    }
    exit;
}

$order_id = $_GET['id'];

// Fetch the current order details
$query = "SELECT id, status FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<p>Order not found.</p>';
    if (file_exists('footer.php')) {
        include 'footer.php';
    }
    exit;
}

$order = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token validation
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo '<p>Invalid CSRF token.</p>';
        if (file_exists('footer.php')) {
            include 'footer.php';
        }
        exit;
    }

    $new_status = $_POST['status'];
    $valid_statuses = ['pending', 'completed', 'cancelled'];

    if (!in_array($new_status, $valid_statuses)) {
        echo '<p>Invalid status.</p>';
    } else {
        // Update the order status
        $update_query = "UPDATE orders SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('si', $new_status, $order_id);

        if ($update_stmt->execute()) {
            echo '<p>Order status updated successfully.</p>';
            $order['status'] = $new_status; // Update status locally
        } else {
            echo '<p>Failed to update order status.</p>';
        }
    }
}
?>

<h1>Update Order Status</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <p>Order ID: <?= htmlspecialchars($order['id']) ?></p>
    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <?php foreach (['pending', 'completed', 'cancelled'] as $status): ?>
            <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                <?= ucfirst($status) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Update Status</button>
</form>

<a href="order_list.php">Back to Orders</a>

<?php
if (file_exists('footer.php')) {
    include 'footer.php';
}
