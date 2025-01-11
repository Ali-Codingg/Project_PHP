<?php

include('config.php');


if(isset($_POST['Add'])){
     $NAME = $_POST['name'];
     $PRICE = $_POST['price'];
     $DESCRIPTION = $_POST['description'];
     $STOCK = $_POST['stock'];
     $IMAGE = $_FILES['image'];
     $image_location = $_FILES['image']['tmp_name'];
     $image_name = $_FILES['image']['name'];
     $image_up = "images/".$image_name;
     $add = "INSERT INTO products (name,description,price,stock,image) VALUES ('$NAME','$DESCRIPTION','$PRICE','$STOCK','$image_up')";

     mysqli_query($conn,$add);

     if(move_uploaded_file($image_location,'images/'.$image_name)){
        echo "<script>alert('It was uploaded successfuly.')</script>";
     }else {
        echo "<script>alert('There is a problem.')</script>";
     }

     header('location: index.php');


}

?>