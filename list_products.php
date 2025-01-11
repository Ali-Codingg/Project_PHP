<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>

    .card{
        float: right;
        margin-top: 20px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .card img{
        width: 100%;
        height: 200px;
    }
    main{
        width: 60%;
    }
    </style>
</head>
<body>
    <center>

      <h2>All Products</h2>

    <center>

    <?php
     include('config.php');
     $result = mysqli_query($conn,"SELECT * FROM products");
     while($row = mysqli_fetch_array($result)){
     echo "
     <center>

       <main>
   <div class='card' style='width: 15rem;'>
  <img src='$row[image]' class='card-img-top'>
      <div class='card-body'>
    <h5 class='card-title'>$row[name]</h5>
    <p class='description'>$row[description]</p>
    <p class='card-text'>$row[price]</p>
    <a href='delete.php? id=$row[id]' class='btn btn-danger'>Remove</a>
    <a href='edit_product.php? id=$row[id]' class='btn btn-primary'>Edite</a>
      </div>
   </div>
   </main>
     <center>
     
     ";
     }
    ?>

</body>
</html>