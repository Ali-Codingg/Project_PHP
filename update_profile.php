<?php
session_start();
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];
$name = htmlspecialchars($_POST['name']);
$email = mysqli_real_escape_string($conn, trim($_POST['email']));

// Update query for user profile
$update = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
$result = mysqli_query($conn, $update);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Updated - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="products.php">Honey E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
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
        <?php if($result): ?>
            <div class="alert alert-success text-center" role="alert">
                <h4 class="alert-heading">Profile Updated Successfully!</h4>
                <p>Your profile has been updated.</p>
                <hr>
                <p class="mb-0">
                    <a href="profile.php" class="btn btn-warning">Go to Your Profile</a>
                    <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
                </p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
                <h4 class="alert-heading">Error</h4>
                <p>There was an error updating your profile. Please try again later.</p>
                <hr>
                <p class="mb-0">
                    <a href="profile.php" class="btn btn-warning">Back to Profile</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
