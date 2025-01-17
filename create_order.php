<?php

session_start();

// Include the configuration file for database connection
include 'config.php';
include 'header.php'; 

// CSRF Token generation for enhanced security (if not already set)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo '<p>Invalid CSRF token.</p>';
        include 'footer.php';
        exit;
    }

    // Sanitize and validate form data
    $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
    $product_ids = array_map('intval', $_POST['product_ids'] ?? []);
    $quantities = array_map('intval', $_POST['quantities'] ?? []);

    // Check if required fields are filled
    if (empty($user_id) || empty($product_ids) || empty($quantities)) {
        echo '<p>All fields are required.</p>';
        include 'footer.php';
        exit;
    } else {
        // Fetch product data for the selected product IDs
        $product_ids_string = implode(',', $product_ids);
        $query = "SELECT id, price, stock FROM products WHERE id IN ($product_ids_string)";
        $result = $conn->query($query);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[$row['id']] = $row;
        }

        $total_amount = 0;
        $order_items = [];

        // Process each product in the order
        foreach ($product_ids as $index => $product_id) {
            if (!isset($products[$product_id])) {
                echo '<p>Product ID ' . htmlspecialchars($product_id) . ' not found.</p>';
                include 'footer.php';
                exit;
            }

            $quantity = $quantities[$index];
            if ($quantity > $products[$product_id]['stock']) {
                echo '<p>Not enough stock for product ID ' . htmlspecialchars($product_id) . '.</p>';
                include 'footer.php';
                exit;
            }

            $subtotal = $quantity * $products[$product_id]['price'];
            $total_amount += $subtotal;

            $order_items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $products[$product_id]['price']
            ];
        }

        // Insert order into the database
        $query = "INSERT INTO orders (user_id, status, total_amount) VALUES (?, 'pending', ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('id', $user_id, $total_amount);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert order items and update product stock
        $query_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($query_item);

        $query_stock = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $stmt_stock = $conn->prepare($query_stock);

        foreach ($order_items as $item) {
            $stmt_item->bind_param('iiid', $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt_item->execute();

            $stmt_stock->bind_param('ii', $item['quantity'], $item['product_id']);
            $stmt_stock->execute();
        }

        // Redirect to order details page with a success message
        header("Location: order_details.php?id=$order_id&success=1");
        exit;
    }
}

// Fetch all customers (users with 'customer' role)
$users_query = "SELECT id, name FROM users WHERE role = 'customer'";
$users_result = $conn->query($users_query);

// Fetch all products (with stock)
$products_query = "SELECT id, name, stock FROM products";
$products_result = $conn->query($products_query);
?>

<h1>Create Order</h1>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label for="user_id">Customer:</label>
    <select name="user_id" id="user_id" required>
        <option value="">Select Customer</option>
        <?php while ($user = $users_result->fetch_assoc()): ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
        <?php endwhile; ?>
    </select><br>

    <label for="product_ids[]">Products:</label><br>
    <?php while ($product = $products_result->fetch_assoc()): ?>
        <input type="checkbox" name="product_ids[]" value="<?= $product['id'] ?>" id="product_<?= $product['id'] ?>" 
               onclick="toggleQuantityInput(<?= $product['id'] ?>)">
        <?= htmlspecialchars($product['name']) ?> (Stock: <?= $product['stock'] ?>)
        <input type="number" name="quantities[]" min="1" placeholder="Quantity" id="quantity_<?= $product['id'] ?>" 
               disabled><br>
    <?php endwhile; ?>

    <button type="submit">Create Order</button>
</form>

<script>
// Enable/disable quantity input based on checkbox selection
function toggleQuantityInput(productId) {
    const checkbox = document.getElementById('product_' + productId);
    const quantityInput = document.getElementById('quantity_' + productId);
    quantityInput.disabled = !checkbox.checked;
}
</script>

<a href="order_list.php">Back to Orders</a>

<?php include 'footer.php'; ?> 
