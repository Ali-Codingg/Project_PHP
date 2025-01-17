<?php
session_start();
include('config.php');

// Fetch products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

// Initialize an array to hold products
$products = [];

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($product = mysqli_fetch_assoc($result)) {
            $products[] = $product;
        }
    } else {
        $products = [];
    }
} else {
    // Handle SQL query error
    $error_message = "An error occurred while fetching products. Please try again later.";
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Custom CSS for additional styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-card {
            margin-bottom: 30px;
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffc107 !important; /* Honey-like color */
        }
        .navbar-nav .nav-link {
            color: #343a40 !important;
        }
        .add-to-cart-btn {
            background-color: #ffc107;
            border: none;
            color: #343a40;
        }
        .add-to-cart-btn:hover {
            background-color: #e0a800;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="products.php">Honey E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mt-5">
        <!-- Display Error Message if any -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="card product-card">
                            <!-- Product Image -->
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                     class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x200.png?text=No+Image" 
                                     class="card-img-top product-image" alt="No Image Available">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <!-- Product Name -->
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                
                                <!-- Product Description -->
                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                
                                <!-- Product Price -->
                                <p class="card-text"><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                                
                                <!-- Product Stock -->
                                <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                                
                                <!-- Add to Cart Form -->
                                <?php if ($product['stock'] > 0): ?>
                                    <form action="add_to_cart.php" method="POST" class="mt-auto">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                        <div class="mb-3">
                                            <label for="quantity_<?php echo htmlspecialchars($product['id']); ?>" class="form-label">Quantity:</label>
                                            <input 
                                                type="number" 
                                                id="quantity_<?php echo htmlspecialchars($product['id']); ?>" 
                                                name="quantity" 
                                                class="form-control" 
                                                value="1" 
                                                min="1" 
                                                max="<?php echo htmlspecialchars($product['stock']); ?>" 
                                                required
                                            >
                                        </div>
                                        <button type="submit" class="btn add-to-cart-btn w-100">Add to Cart</button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center" role="alert">
                                        Out of Stock
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No products available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper (for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Custom JS for additional functionality -->
</body>
</html>

