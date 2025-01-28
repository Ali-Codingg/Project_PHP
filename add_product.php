<?php
// Include the database configuration
session_start();
include('config.php');
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['Add'])) {
    // Retrieve form inputs
    $NAME = $_POST['name'];
    $PRICE = $_POST['price'];
    $DESCRIPTION = $_POST['description'];
    $STOCK = $_POST['stock'];

    // Handle file upload
    $IMAGE = $_FILES['image'];
    $image_location = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $image_up = "assets/images/" . $image_name;


    // SQL query to insert product into the database
    $add = "INSERT INTO products (name, description, price, stock, image) 
            VALUES ('$NAME', '$DESCRIPTION', '$PRICE', '$STOCK', '$image_up')";

    if (mysqli_query($conn, $add)) {
        // Move the uploaded file to the `images` directory
        if (move_uploaded_file($image_location, $image_up)) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "<script>alert('Failed to upload the image.');</script>";
        }
    } else {
        echo "<script>alert('Error adding product: " . mysqli_error($conn) . "');</script>";
    }
    header("Location:products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Honey E-Commerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Custom CSS for consistent design -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffc107 !important;
        }
        .navbar-nav .nav-link {
            color: #343a40 !important;
        }
        .card-header {
            background-color: #ffc107;
            color: #343a40;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar (similar to products.php) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Honey E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- You can add menu items as needed -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_product.php">Add Product</a>
                    </li>
                    <!-- Optionally, links to Login/Logout based on session -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card Container to match the style from products.php -->
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3 class="mb-0">Add New Product</h3>
                    </div>
                    <div class="card-body">
                        <form action="add_product.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="name" 
                                    name="name" 
                                    placeholder="Enter product name" 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price ($)</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="price" 
                                    name="price" 
                                    placeholder="Enter product price" 
                                    step="0.01" 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea 
                                    class="form-control" 
                                    id="description" 
                                    name="description" 
                                    placeholder="Enter product description" 
                                    rows="4" 
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="stock" 
                                    name="stock" 
                                    placeholder="Enter stock quantity" 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input 
                                    type="file" 
                                    class="form-control" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*" 
                                    required>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="Add" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
