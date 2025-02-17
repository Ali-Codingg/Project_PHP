<?php
session_start();
include("config.php");

// Ensure the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Retrieve the shipping address from POST data (ensure your checkout form has a field named "shipping_address")
$shipping_address = isset($_POST['shipping_address']) ? $_POST['shipping_address'] : '';
// Get the current date and time for the order
$order_date = date("Y-m-d H:i:s");

// 1. Insert a new order record with shipping_address, order_date, and a temporary total_amount of 0
$insertOrder = "INSERT INTO orders (user_id, total_amount, shipping_address, order_date) 
                VALUES ('$user_id', 0, '$shipping_address', '$order_date')";
mysqli_query($conn, $insertOrder);
$order_id = mysqli_insert_id($conn); // Retrieve the last inserted order ID

// 2. Retrieve the cart items for the current user
$sql = "SELECT c.product_id, c.quantity, p.name, p.description, p.price, p.image
        FROM cart c
        LEFT JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

$total = 0;
while($item = mysqli_fetch_assoc($result)) {
    $subtotal = $item["price"] * $item["quantity"];
    $total += $subtotal;
    
    // 3. Insert into order_items table with order_id, product_id, quantity, and price
    $insertDetail = "INSERT INTO order_items (order_id, product_id, quantity, price)
                     VALUES ('$order_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')";
    mysqli_query($conn, $insertDetail);
    
    // 4. Update the product stock (reduce available stock by the quantity ordered)
    $updateStock = "UPDATE products 
    SET stock = GREATEST(stock - '{$item['quantity']}', 0) 
    WHERE id = '{$item['product_id']}'";
mysqli_query($conn, $updateStock);

}

// 5. Update the order record with the calculated total amount
$updateOrder = "UPDATE orders SET total_amount = '$total' WHERE id = '$order_id'";
mysqli_query($conn, $updateOrder);

// 6. Clear the user's cart since the order has been processed
$clearCart = "DELETE FROM cart WHERE user_id = '$user_id'";
mysqli_query($conn, $clearCart);

mysqli_close($conn);

// Redirect to order confirmation page with the order ID in the URL
header("Location: order_confirmation.php?order_id=" . $order_id);
exit();
?>
