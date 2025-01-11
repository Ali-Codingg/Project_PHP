<?php
session_start();
include('config.php');

// Initialize variables for messages
$error = "";
$success = "";

// Check if there's a logout success message
if (isset($_SESSION['logout_success'])) {
    $success = $_SESSION['logout_success'];
    unset($_SESSION['logout_success']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Construct the SQL query
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($password, $user["password"])) {
                // Set session variables (excluding password for security)
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION['user_role'] = $user['role'];
                // It's insecure to store the password hash in session; remove it
                // $_SESSION["password"] = $user["password"];
                
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Redirect to products page
                header("Location: products.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No user found with this email.";
        }
    } else {
        // Handle SQL query error
        $error = "An error occurred. Please try again later.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Custom CSS for additional styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            margin-top: 50px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
        }

        .login-card-footer {
            background-color: #fff;
            border-top: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Center the form vertically and horizontally -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Login Card -->
                <div class="card login-card">
                    <div class="card-header login-card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display Success Message -->
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Display Error Message -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form action="login.php" method="POST">
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    required 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                    placeholder="Enter your email address"
                                >
                            </div>
                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password:</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    placeholder="Enter your password"
                                >
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer login-card-footer">
                        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
                    </div>
                </div>
                <!-- End of Login Card -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper (for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Custom JS for additional functionality -->
</body>
</html>
