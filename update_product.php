<?php
include('config.php');

if (isset($_POST['update'])) {
    $ID = $_POST['product_id'];
    $NAME = $_POST['name'];
    $PRICE = $_POST['price'];
    $DESCRIPTION = $_POST['description'];
    $STOCK = $_POST['stock'];

    // Check if a new image was uploaded by verifying if the file name is not empty
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $image_location = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_up = "assets/images/" . $image_name;
        
        // Update including the image field
        $update = "UPDATE products SET 
                    name = '$NAME', 
                    description = '$DESCRIPTION', 
                    stock = '$STOCK', 
                    price = '$PRICE', 
                    image = '$image_up' 
                   WHERE id = $ID";
        mysqli_query($conn, $update);
        
        // Attempt to move the uploaded file
        if (move_uploaded_file($image_location, 'assets/images/' . $image_name)) {
            echo "<script>alert('Product updated successfully with new image.');</script>";
        } else {
            echo "<script>alert('There was a problem uploading the image.');</script>";
        }
    } else {
        // No new image provided; update only the other fields
        $update = "UPDATE products SET 
                    name = '$NAME', 
                    description = '$DESCRIPTION', 
                    stock = '$STOCK', 
                    price = '$PRICE' 
                   WHERE id = $ID";
        mysqli_query($conn, $update);
        echo "<script>alert('Product updated successfully without changing the image.');</script>";
    }

    header('location:products.php');
    exit();
}
?>
