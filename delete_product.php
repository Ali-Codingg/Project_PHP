<?php

include('config.php');

$product_id=$_POST["product_id"];

mysqli_query($conn, "DELETE FROM products WHERE id=$product_id");
header('location:products.php');

?>