<?php
session_start();
include('config.php');

// Ensure the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: products.php");
    exit();
}

// Query 1: Total Orders
$sql_total_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$result_total_orders = mysqli_query($conn, $sql_total_orders);
$row_total_orders = mysqli_fetch_assoc($result_total_orders);
$total_orders = $row_total_orders['total_orders'];

// Query 2: Total Revenue
$sql_total_revenue = "SELECT SUM(total_amount) AS total_revenue FROM orders";
$result_total_revenue = mysqli_query($conn, $sql_total_revenue);
$row_total_revenue = mysqli_fetch_assoc($result_total_revenue);
$total_revenue = $row_total_revenue['total_revenue'];

// Query 3: Total Users
$sql_total_users = "SELECT COUNT(*) AS total_users FROM users";
$result_total_users = mysqli_query($conn, $sql_total_users);
$row_total_users = mysqli_fetch_assoc($result_total_users);
$total_users = $row_total_users['total_users'];

// Query 4: Total Products Sold (sum of quantity in order_items)
$sql_total_products_sold = "SELECT SUM(quantity) AS total_products_sold FROM order_items";
$result_total_products_sold = mysqli_query($conn, $sql_total_products_sold);
$row_total_products_sold = mysqli_fetch_assoc($result_total_products_sold);
$total_products_sold = $row_total_products_sold['total_products_sold'];

// Query 5: Most Popular Product (by quantity sold)
$sql_popular_product = "SELECT p.name, SUM(oi.quantity) AS total_sold 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        GROUP BY p.id 
                        ORDER BY total_sold DESC 
                        LIMIT 1";
$result_popular_product = mysqli_query($conn, $sql_popular_product);
$popular_product = mysqli_fetch_assoc($result_popular_product);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Statistics - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
        }
        .card {
            margin-bottom: 20px;
        }
        .dashboard-header {
            margin-top: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Honey E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link" href="products.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_orders.php">Manage Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Container -->
    <div class="container">
        <div class="dashboard-header text-center">
            <h2>Admin Dashboard</h2>
            <p>Overview of key metrics and statistics</p>
        </div>
        
        <!-- Top Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?php echo $total_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <p class="card-text">$<?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Products Sold</h5>
                        <p class="card-text"><?php echo $total_products_sold; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Stats: Most Popular Product -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Most Popular Product
                    </div>
                    <div class="card-body">
                        <?php if ($popular_product): ?>
                            <h5 class="card-title"><?php echo htmlspecialchars($popular_product['name']); ?></h5>
                            <p class="card-text">Total Sold: <?php echo $popular_product['total_sold']; ?></p>
                        <?php else: ?>
                            <p>No sales data available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
