<?php

include('config.php');


if(isset($_POST['update'])){
     $ID = $_POST['id'];
     $NAME = $_POST['name'];
     $PRICE = $_POST['price'];
     $DESCRIPTION = $_POST['description'];
     $STOCK = $_POST['stock'];
     $IMAGE = $_FILES['image'];
     $image_location = $_FILES['image']['tmp_name'];
     $image_name = $_FILES['image']['name'];
     $image_up = "images/".$image_name;
     $update = "UPDATE products SET name='$NAME',description='$DESCRIPTION',price='$PRICE',image='$image_up' WHERE id=$ID";

     mysqli_query($conn,$update);

     if(move_uploaded_file($image_location,'images/'.$image_name)){
        echo "<script>alert('It was updated successfuly.');</script>";
     }else {
        echo "<script>alert('There is a problem.');</script>";
     }

     header('location: index.php');


}

?>