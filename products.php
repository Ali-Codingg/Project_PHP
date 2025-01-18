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
    }
} else {
    // Handle SQL query error
    $error_message = "An error occurred while fetching products. Please try again later.";
}

mysqli_close($conn);

// Get the product id that was added, if any (for alert messages)
$added_id = isset($_GET['added']) ? $_GET['added'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Product Card */
        .product-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        /* Product Image */
        .product-image {
            width: 100%;
            height: 200px;       /* Fixed height for all images */
            object-fit: cover;   /* Ensures image covers the entire container */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        /* Card Title */
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }
        /* Card Text */
        .card-text {
            color: #555;
        }
        /* Add to Cart Button */
        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #343a40;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            color: #fff;
        }
        /* Delete Button */
        .delete-btn {
            background-color: #dc3545;
            border: none;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        /* Align text and buttons inside card-body */
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
                        <!-- Show Cart link only for non-admin users -->
                        <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="view_cart.php">Cart</a>
                        </li>
                        <?php endif; ?>
                        
                        <!-- Admin-only link: Manage Orders -->
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_orders.php">Manage Orders</a>
                        </li>
                        <?php endif; ?>
                        
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

        <!-- Products Grid: Using row-cols-md-3 for 3 cards per row -->
        <div class="row row-cols-md-3 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card product-card h-100 position-relative">
                            <?php if ($added_id && $added_id == $product['id']): ?>
                                <div class="alert alert-success alert-dismissible fade show position-absolute" 
                                    style="top: 10px; right: 10px; z-index: 10; min-width: 200px;">
                                    Product added to cart!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <!-- Product Image -->
                            <img src="<?php echo htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/300x200.png?text=No+Image'); ?>" 
                                class="card-img-top product-image" 
                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <!-- Product Name -->
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                
                                <!-- Product Description -->
                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                
                                <!-- Product Price -->
                                <p class="card-text"><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                                
                                <!-- Product Stock -->
                                <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                                
                                <!-- Only show Add to Cart for non-admin users -->
                                <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                                    <form action="add_to_cart.php" method="POST" class="mt-auto">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                        <label for="quantity_<?php echo htmlspecialchars($product['id']); ?>">Quantity:</label>
                                        <input type="number" id="quantity_<?php echo htmlspecialchars($product['id']); ?>" 
                                            name="quantity" class="form-control mb-2" value="1" 
                                            min="1" max="<?php echo htmlspecialchars($product['stock']); ?>" required>
                                        <button type="submit" class="btn btn-warning w-100">Add to Cart</button>
                                    </form>
                                <?php endif; ?>
                                
                                <!-- Admin Controls: Edit and Delete (visible only to admin users) -->
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <div class="mt-3 d-flex justify-content-between">
                                        <!-- Edit Button -->
                                        <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary w-50 me-1">Edit</a>
                                        <!-- Delete Button -->
                                        <form action="delete_product.php" method="POST" class="w-50 ms-1">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                            <button type="submit" class="btn delete-btn w-100">Delete</button>
                                        </form>
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
