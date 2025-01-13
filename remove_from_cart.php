<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    
    // Delete the item from the cart for the current user
    $sql = "DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    mysqli_query($conn, $sql);
}

mysqli_close($conn);
header("Location: view_cart.php");
exit();
?>
