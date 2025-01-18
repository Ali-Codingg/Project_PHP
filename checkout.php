<?php
session_start();
include("config.php");

if(!isset($_SESSION["user_id"])){
    header("Location:login.php");
    exit();
}
$user_id= $_SESSION["user_id"];

$sql="SELECT c.product_id,c.quantity, p.name, p.description, p.price, p.image
      FROM cart c
      LEFT JOIN products p
      ON c.product_id=p.id WHERE c.user_id='$user_id'";

$result=mysqli_query($conn,$sql);
$cart_items=[];
$total=0;
if(mysqli_num_rows($result)> 0){
  while($row=mysqli_fetch_assoc($result)){
    $cart_items[]=$row;
    $total += $row['price'] * $row['quantity'];
  }
}else {
    $error_message = "An error occurred while fetching your cart. Please try again later.";
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Honey E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
    <!-- (Include your navigation bar here) -->
    
    <div class="container mt-5">
        <h2 class="mb-4">Checkout</h2>
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if(empty($cart_items)): ?>
            <div class="alert alert-info">Your cart is empty. <a href="products.php">Go shopping!</a></div>
        <?php else: ?>
            <!-- Display Order Summary -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Checkout Form -->
            <form action="checkout_process.php" method="POST">
                <div class="mb-3">
                    <label for="address" class="form-label">Shipping Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <!-- Additional fields (like phone number or payment details) could be added here -->
                <button type="submit" class="btn btn-success">Place Order</button>
            </form>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>