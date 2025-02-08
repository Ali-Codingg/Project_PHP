<?php
session_start();
include('config.php');

// Fetch all products from the database
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

// Filter the products to get only those with stock 5 or less
$featuredProducts = array_filter($products, function($product) {
    return $product['stock'] <= 5;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Hero/Banner Section */
        .hero-section {
            background: url('images/hero.jpg') no-repeat center center;
            background-size: cover;
            height: 400px;
            position: relative;
            color: #fff;
        }
        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .hero-text h1 {
            font-size: 3rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        .hero-text p {
            font-size: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }
        /* Featured Products Card */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        /* Review Text Styling */
        .review-text {
            font-style: italic;
            color: #888;
        }
        /* Footer Styling */
        footer {
            background-color: #fff;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 -1px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Honey E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Show Cart for non-admin users -->
                        <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="view_cart.php">Cart</a>
                        </li>
                        <?php endif; ?>
                        <!-- Show Manage Orders for admin users -->
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

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-text">
            <h1>Welcome to Honey E-Commerce</h1>
            <p>Discover our pure organic honey products!</p>
            <a href="products.php" class="btn btn-warning btn-lg">Shop Now</a>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <?php if (!empty($featuredProducts)): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/300x200.png?text=No+Image'); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <!-- Display short review if available -->
                            <?php if (isset($product['review']) && !empty($product['review'])): ?>
                                <p class="review-text"><?php echo htmlspecialchars($product['review']); ?></p>
                            <?php endif; ?>
                            <p class="card-text"><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="products.php" class="btn btn-warning w-100">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-center">No featured products available at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- About Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">About Us</h2>
        <p>
            Honey E-Commerce is your trusted source for premium, organic honey products. Our mission is to provide high-quality honey that is both delicious and sustainable. Explore our unique range of honey products, and enjoy a taste that’s as natural as it gets.
        </p>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Honey E-Commerce. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
