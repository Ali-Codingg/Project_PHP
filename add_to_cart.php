<?php
session_start();
include("config.php");
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

$sql="SELECT * FROM cart WHERE user_id='$user_id'
      AND product_id='$product_id'";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result)> 0){
    $update = "UPDATE cart SET quantity = quantity + '$quantity' WHERE user_id='$user_id' AND product_id='$product_id'";
}
else{
    $update= "INSERT INTO cart(user_id, product_id, quantity)
              VALUES('$user_id','$product_id','$quantity') ";
}
mysqli_query($conn,$update);
echo "Product added to cart successfully!";
mysqli_close( $conn );
header("Location: products.php?added=" . urlencode($product_id));
exit();
?>
