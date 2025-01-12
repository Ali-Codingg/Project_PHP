<?php
// Include the configuration file
include("config.php");

// Start the session
session_start();

// Handle logout request
if (isset($_POST['logout'])) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_pannel.php" class="logo"><img src="img/logo.png" alt="Logo"></a>
            <nav class="navbar">
                <a href="home.php">Home</a>
                <a href="about.php">About</a>
                <a href="shop.php">Shop</a>
                <a href="order.php">Order</a>
                <a href="contact.php">Contact</a>
            </nav>
            <div class="icons">
                <i class="bi bi-person" id="user-btn"></i>
                <a href="wishlist.php"><i class="bi bi-heart"></i></a>
                <a href="cart.php"><i class="bi bi-cart"></i></a>
                <i class="bi bi-list" id="menu-btn"></i>
            </div>
            <div class="user-box">
                <p>Username: 
                    <span>
                        <?php 
                        // Display username if set, otherwise show "Guest"
                        echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; 
                        ?>
                    </span>
                </p>
                <p>Email: 
                    <span>
                        <?php 
                        // Display email if set, otherwise show "Not Available"
                        echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'Not Available'; 
                        ?>
                    </span>
                </p>
                <form method="post">
                    <button type="submit" name="logout" class="logout-btn">Log Out</button>
                </form>
            </div>
        </div>
    </header>
</body>
</html>