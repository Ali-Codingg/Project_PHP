<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="index.css" class="css">
</head>
<body>

  <?php 
    include('config.php');
    $ID = $_GET['id'];
    $up = mysqli_query($conn,"SELECT * FROM products where id=$ID");
    $data = mysqli_fetch_array($up);
  ?>

  <center>
    <div class="main">
        <form action="up.php" method="post" enctype="multipart/form-data">
            <h2>Edit Product</h2>
            <input type="text" style="display: none;" name="id" placeholder="Enter the name" displa value="<?php echo $data['id'] ?>"><br/>
            <input type="text" name="name" placeholder="Enter the name" value="<?php echo $data['name'] ?>"><br/>
            <input type="text" name="description" placeholder="description" value="<?php echo $data['description'] ?>"><br/>
            <input type="text" name="price" placeholder="price($)" value="<?php echo $data['price'] ?>"><br/>
            <input type="text" name="stock" placeholder="stock" value="<?php echo $data['stock'] ?>"><br/>
            <input type="file" id="file" name="image" style='display:none'>
            <label for="file">Upload Image</label >
            <button name='update' type="submit">Update</button><br/><br/>
            <a href="list_products.php">View All Products</a>
        </form>
    </div>
  </center>
    
</body>
</html>