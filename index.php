<?php
session_start();
include('config.php');

// Optionally, you could fetch featured products here
// For example:
// $sqlFeatured = "SELECT * FROM products WHERE featured=1 LIMIT 3";
// $resultFeatured = mysqli_query($conn, $sqlFeatured);
// $featuredProducts = [];
// if($resultFeatured){
//     while($row = mysqli_fetch_assoc($resultFeatured)){
//         $featuredProducts[] = $row;
//     }
// }
// mysqli_close($conn);
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
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }
        .hero-text p {
            font-size: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }
        /* Featured Products */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
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
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Show Cart for non-admin users -->
                        <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="view_cart.php">Cart</a>
                            </li>
                        <?php endif; ?>
                        <!-- Show Manage Orders link for admin users -->
                        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
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
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php 
            // If you have dynamic featured products, loop through them.
            // For demonstration, we'll create three dummy cards.
            for ($i = 1; $i <= 3; $i++):
            ?>
            <div class="col">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200?text=Product+<?php echo $i; ?>" class="card-img-top" alt="Product <?php echo $i; ?>">
                    <div class="card-body">
                        <h5 class="card-title">Product <?php echo $i; ?></h5>
                        <p class="card-text">Short description of Product <?php echo $i; ?>.</p>
                    </div>
                    <div class="card-footer">
                        <a href="products.php" class="btn btn-warning w-100">View Product</a>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
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
